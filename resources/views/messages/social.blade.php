<?php
    $dnl="\n\n";
    $nl="\n\n";
    $tabSpace="      ";
?>
{{ __("Hi, I'd like to place an order")." 👇"}}

@if ($order->delivery_method==1)
🛵🔜🏡
{{"*".__('Delivery Order No').": ".$order->id."*"}}
@else
✅🏫
{{"*".__('Pickup Order No').": ".$order->id."*"}}
@endif

---------
<?php
foreach ($order->items()->get() as $key => $item) {
    $lineprice = $item->pivot->qty.' X '.$item->name." - ".money($item->pivot->qty * $item->pivot->variant_price, config('settings.cashier_currency'), true);
    if(strlen($item->pivot->variant_name)>3){
        $lineprice .=$nl.$tabSpace.__('Variant:')." ".$item->pivot->variant_name;
    }
   
    if(strlen($item->pivot->extras)>3){
        foreach (json_decode($item->pivot->extras) as $key => $extra) {
            $lineprice .=$nl.$tabSpace.$extra;
        }
    }
?>
🔘{{ $lineprice }}

<?php
}
?>
---------
@if ($order->delivery_method==1)
🗒 {{ __('Sub total').": ".money(($order->order_price), config('settings.cashier_currency'), config('settings.do_convertion')) }}
🛵 {{ __('Delivery').": ".money(($order->delivery_price), config('settings.cashier_currency'), config('settings.do_convertion')) }}
@endif
🧾 {{__('Total: ').money(($order->order_price+$order->delivery_price), config('settings.cashier_currency'), config('settings.do_convertion')) }}
---------

@if (strlen($order->comment)>0)   
🗒 {{ __('Comment') }}
{{ $order->comment }}  
@endif

<?php  //Deliver / Pickup details ?>
@if($order->delivery_method==1)
<?php  //Deliver?>
📍 {{ __('Delivery Details') }}

@if(config('app.isft'))
{{ __('Client').": ".$order->client->name }}
{{ __('Address').": ".$order->address->address }}
{{ __('Delivery time').": ".$order->getTimeFormatedAttribute() }}
@else
{{ __('Customer name').": ". ($order->configs&&isset($order->configs['client_name'])?$order->configs['client_name']:"") }}
{{ __('Customer phone').": ". ($order->configs&&isset($order->configs['client_phone'])?$order->configs['client_phone']:"") }}
{{ __('Address').": ".$order->whatsapp_address }}
@if(config('app.iswp')) 
{{ __('Delivery Area').": ".($order->configs&&isset($order->configs['delivery_area_name'])?$order->configs['delivery_area_name']:"") }}
@endif
{{ __('Delivery time').": ".$order->getTimeFormatedAttribute() }}
@endif

@else
<?php   //Pickup details ?>
✅ {{ __('Pickup Details') }}

@if(config('app.isft'))
{{ __('Client').": ".$order->client->name }}
{{ __('Pickup time').": ".$order->getTimeFormatedAttribute() }}
@else
{{ __('Customer name').": ". ($order->configs&&isset($order->configs['client_name'])?$order->configs['client_name']:"") }}
{{ __('Customer phone').": ". ($order->configs&&isset($order->configs['client_phone'])?$order->configs['client_phone']:"") }}
{{ __('Pickup time').": ".$order->getTimeFormatedAttribute() }}
@endif

@endif

<?php   //Custom fields ?>
<?php $custom_data=$order->getAllConfigs(); ?>
@if(count($custom_data)>0)
{{ __(config('settings.label_on_custom_fields')) }}
@foreach ($custom_data as $keyCutom => $itemValue)
{{ __( "custom.".$keyCutom) }}: {{ $itemValue }}
@endforeach
@endif


{{ $order->restorant->name." ".__('will confirm your order upon receiving the message.') }}


<?php //Add payment only in whatsapp ordering mode ?>
@if(config('settings.is_whatsapp_ordering_mode'))   
<?php //Payment ?>
💳 {{ __('Payment Options') }}
{{ $order->restorant->payment_info }}

<?php //Payment Link ?>

@if(strlen($order->payment_link)>5)
<?php //Add the payment link ?>
💳 {{ __('Pay now') }}
{{ $order->restorant->getLinkAttribute()."/?pay=".$order->id }}
@endif
@endif
