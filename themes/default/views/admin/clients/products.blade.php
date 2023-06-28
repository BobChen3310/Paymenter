<x-admin-layout title="Products/Services of {{ $user->name }}">
    <div class="w-full h-full rounded mb-4">
        <div class="px-6 mx-auto">
            <div class="flex flex-row overflow-x-auto lg:flex-wrap lg:space-x-1">
                <div class="flex-none">
                    <a href="{{ route('admin.clients.edit', $user->id) }}"
                        class="inline-flex justify-center w-full p-4 px-2 py-2 text-xs font-bold text-gray-900 uppercase border-b-2 dark:text-darkmodetext dark:hover:bg-darkbutton hover:border-logo hover:text-logo @if (request()->routeIs('admin.clients.edit')) border-logo @else border-y-transparent @endif">
                        {{ __('Client Details') }}
                    </a>
                </div>
                <div class="flex-none">
                    <a href="{{ route('admin.clients.products', $user->id) }}"
                        class="inline-flex justify-center w-full p-4 px-2 py-2 text-xs font-bold text-gray-900 uppercase border-b-2 dark:text-darkmodetext dark:hover:bg-darkbutton hover:border-logo hover:text-logo @if (request()->routeIs('admin.clients.products*')) border-logo @else border-y-transparent @endif">
                        {{ __('Products/Services') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        function change() {
            window.location.href = "{{ route('admin.clients.products', $user->id) }}/" + document.getElementById('product')
                .value;
        }
    </script>
    <div class="mb-4">
        <x-input type="select" name="product" id="product" onchange="change()">
            <option value="0" selected disabled>{{ __('Select Product/Service') }}</option>
            @foreach ($orderProducts as $product)
                <option value="{{ $product->id }}" @if ($product->id === $orderProduct->id) selected @endif>
                    {{ $product->product->name }} ({{ $product->id }})
                </option>
            @endforeach
        </x-input>
    </div>
    <h1 class="text-2xl my-2">Order Product Details:</h1>
    <form action="{{ route('admin.clients.products.update', [$user->id, $orderProduct->id]) }}" method="POST"
        class="flex flex-col gap-2">
        @csrf
        <div class="grid grid-cols-2 gap-8">
            <div class="flex flex-col gap-4">
                <h3 class="text-lg border-b mb-1 border-gray-500 fon">Order ID: <a
                        href="{{ route('admin.orders.show', $orderProduct->order->id) }}"
                        class="text-logo">#{{ $orderProduct->order->id }}</a></h3>
                <div class="flex gap-2">
                    <x-input type="select" name="action" id="action" label="Extension Settings" class="w-full" onchange="document.getElementById('statusinput').value = this.value">
                        <option selected disabled>{{ __('Select Action') }}</option>
                        <option value="create">{{ __('Create') }}</option>
                        <option value="suspend">{{ __('Suspend') }}</option>
                        <option value="unsuspend">{{ __('Unsuspend') }}</option>
                        <option value="terminate">{{ __('Terminate') }}</option>
                    </x-input>
                    <button class="button button-primary h-fit self-end" 
                        onclick="event.preventDefault(); document.getElementById('changestatus').submit();"
                    >{{ __('Go') }}</button>
                </div>
                <x-input type="text" disabled name="created_at" id="created_at"
                    value="{{ $orderProduct->created_at }}" label="Created At" />
                <x-input type="select" name="status" id="status" label="Status">
                    <option value="0" selected disabled>{{ __('Select Status') }}</option>
                    <option value="pending" {{ $orderProduct->status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="paid" {{ $orderProduct->status === 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                    <option value="cancelled" {{ $orderProduct->status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                    <option value="suspended" {{ $orderProduct->status === 'suspended' ? 'selected' : '' }}>{{ __('Suspended') }}</option>
                </x-input>
            </div>
            <div class="flex flex-col gap-4">
                <h3 class="text-lg border-b mb-1 border-gray-500 fon">Product ID: <a
                        href="{{ route('admin.products.edit', $orderProduct->product->id) }}"
                        class="text-logo">#{{ $orderProduct->product->id }}</a></h3>
                <x-input type="date" name="expiry_date" id="expiry_date" value="{{ $orderProduct->expiry_date }}"
                    label="Expiry Date" />
                <x-input type="number" name="quantity" id="quantity" value="{{ $orderProduct->quantity }}"
                    label="Quantity" />
                <x-input type="number" name="price" id="price" value="{{ $orderProduct->price }}"
                    label="Price" />
            </div>
        </div>
        <button class="button button-primary self-end">{{ __('Save') }}</button>
    </form>
    <form action="{{ route('admin.clients.products.changestatus', [$user->id, $orderProduct->id]) }}" method="POST"
        class="hidden" id="changestatus">
        @csrf
        <input type="hidden" name="status" id="statusinput">
    </form>


    <div class="">
        <h1 class="text-2xl my-2">{{ __('Configurable Options') }}</h1>
        @foreach ($configurableOptions as $configurableOption)
            <div class="flex flex-col md:flex-row justify-between items-center border-b border-gray-500 py-2">
                @if ($configurableOption->configurableOption())
                    @php $configurableOption = $configurableOption->configurableOption @endphp
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <h2 class="text-lg">{{ $configurableOption->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $configurableOption->configurableOptionInput->name }}
                        </p>
                    </div>
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-lg">{{ $configurableOption->value }}</p>
                        <button class="button button-primary">Edit</button>
                    </div>
                @else
                    <form
                        action="{{ route('admin.clients.products.config.update', [$user->id, $orderProduct->id, $configurableOption->id]) }}"
                        method="POST" class="flex-row flex items-center w-full gap-6">
                        @csrf
                        <x-input type="text" name="key" id="key" value="{{ $configurableOption->key }}"
                            label="Key" />
                        <x-input type="text" name="value" id="value" value="{{ $configurableOption->value }}"
                            label="Value" />
                        <button class="button button-primary self-end">Update</button>

                    </form>
                @endif
            </div>
        @endforeach

    </div>
    </div>
</x-admin-layout>
