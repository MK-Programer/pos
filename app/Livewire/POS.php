<?php

namespace App\Livewire;

use App\Enums\NotificationType;
use App\Livewire\Widgets\Notifications\Notify;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SalesItem;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class POS extends Component
{
    //* properties 
    public $items,
        $customers,
        $paymentMethods,
        $search = '',
        $cart   = [],

        //* properties for checkout
        $customerId = null,
        $paymentMethodId = null,
        $paidAmount = 0,
        $discountAmount = 0;



    public function mount()
    {
        //* load all the items
        $this->items = Item::whereHas(
                'inventory',
                function (Builder $builder) {
                    $builder->where('quantity', '>', 0);
                }
            )
            ->where('status', 'active')
            ->get();
            
        //* load customers
        $this->customers = Customer::all();

        //* load payment methods
        $this->paymentMethods = PaymentMethod::all();
    }

    #[Computed]
    public function filteredItems()
    {
        if(empty($this->search))
        {
            return $this->items;
        }
        
        return $this->items->filter(function($item){
                return str_contains(strtolower($item->name), strtolower($this->search))
                    || str_contains(strtolower($item->sku), strtolower($this->search));
            });
       
    }

    #[Computed]
    public function subtotal()
    {
        return collect($this->cart)
                ->sum(fn($item) => $item['quantity'] * $item['price']);
    }

    //* placeholder for tax
    #[Computed]
    public function tax()
    {
        return $this->subtotal * 0.15; // 15%
    }

    #[Computed]
    public function totalBeforeDiscount()
    {
        return $this->subtotal + $this->tax;
    }

    #[Computed]
    public function total()
    {
        $discountedTotal = $this->totalBeforeDiscount - $this->discountAmount;
        return $discountedTotal;
    }

    #[Computed]
    public function change()
    {
        if($this->paidAmount > $this->total)
        {
            return $this->paidAmount - $this->total;
        }

        return 0;
    }

    public function addToCart($itemId)
    {
        $item = Item::find($itemId);

        $inventory = $item->inventory;
        //! dead code
        if(!$inventory || $inventory->quantity <= 0)
        {
            Notify::send('Out of stock :(', type: NotificationType::DANGER);
            return;
        }
        
        if(isset($this->cart[$itemId]))
        {
            $cartItem = $this->cart[$itemId];
            $currentQuantity = $cartItem['quantity'];
            if($currentQuantity + 1 > $inventory->quantity)
            {
                Notify::send("Cannot add more, Only $inventory->quantity in stock", type: NotificationType::DANGER);
                return;
            }
            else
            {
                //* add more from this item
                $cartItem['quantity']++;
                $this->cart[$itemId] = $cartItem; 
            }
        }
        else
        {
            $cartItem = [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'price' => $item->price,
                'quantity' => 1,
            ];
            $this->cart[$itemId] = $cartItem;
        }
    }

    //* remove item from the cart
    public function removeFromCart($itemId)
    {
        unset($this->cart[$itemId]);
    } 

    //* updating quantity of item from cart
    public function updateCartItemQuantity($itemId, $quantity) 
    {
        if($quantity < 1)
        {
            $this->removeFromCart($itemId);
            return;
        }

        $inventory = Inventory::whereItemId($itemId)->first();
        $cartItem = $this->cart[$itemId];
        if($quantity > $inventory->quantity)
        {
            Notify::send("Cannot add more, Only $inventory->quantity in stock", type: NotificationType::DANGER);
            $cartItem['quantity'] = $inventory->quantity;
        }
        else
        {
            $cartItem['quantity'] = $quantity;
        }

        $this->cart[$itemId] = $cartItem;
    }

    //* checkout
    public function checkout()
    {
        //* check if the cart is empty
        if(empty($this->cart))
        {
            Notify::send('Failed sale', 'Your cart is empty', NotificationType::DANGER);
            return;
        }
        
        //* basic validation for paid amount
        if($this->paidAmount < $this->total)
        {
            Notify::send('Failed sale', 'Paid amount is less than the total amount', NotificationType::DANGER);
            return;
        }

        try{
            //* create the sale with db transaction
            DB::beginTransaction();

            //* sale
            $sale = Sale::create([
                'customer_id' => $this->customerId,
                'payment_method_id' => $this->paymentMethodId,
                'total' => $this->total, //* after tax and discount
                'paid_amount' => $this->paidAmount,
                'discount' => $this->discountAmount,
            ]);

            //* sale items 
            foreach($this->cart as $item)
            {
                SalesItem::create([
                    'sale_id' => $sale->id,
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                //* update the stock
                $inventory = Inventory::where('item_id', $item['id'])->first(); 
                $inventory->quantity -= $item['quantity'];
                $inventory->save();
            }

            DB::commit();

            //reset
            $this->resetPOS();

            Notify::send('Success sale', 'Sale was made successfull');
        }catch(Exception $e){
            DB::rollBack();
            Notify::send('Failed sale', 'Failed to complete the sale, try again', NotificationType::DANGER);
        }
    } 

    public function resetPOS()
    {
        $this->search = '';
        $this->cart = [];
        $this->customerId = null;
        $this->paymentMethodId = null;
        $this->discountAmount = 0;
        $this->paidAmount = 0;
    }

    public function render()
    {
        return view('livewire.p-o-s');
    }
}
