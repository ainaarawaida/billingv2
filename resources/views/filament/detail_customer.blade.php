
@php
    $data = $this->form->getRawState();

    
    $customer = App\Models\Customer::where('id', $data['customer_id'])?->first();
 


 
@endphp

    <!-- json_encode($this->record) -->

    <!-- json_encode($data) -->

<div class="text-xl">
  <span class="text-primary-500 font-medium">
  @if ($data['customer_id'] == null)

    No Customer Selected

  @else
    <b>{{ $customer['name'] }}</b><br>
    Email: {{ $customer['email'] }}<br>
    Phone: {{ $customer['phone'] }}<br>
  @endif
</span>
</div>
