<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Confirmar pedido - {{ $establishment->name }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-zinc-50">

    <main class="mx-auto max-w-2xl px-4 py-8">

        <div class="mb-8">

            <h1 class="text-3xl font-bold">
                Confirmar pedido
            </h1>

            <p class="mt-2 text-sm text-zinc-500">
                {{ $establishment->name }}
            </p>

        </div>


            <form
                method="POST"
                action="{{ route('menu.checkout.store', $establishment->slug) }}"
                class="flex flex-col gap-6"
            >

            @csrf
            <div id="cart-inputs"></div>


            {{-- Cliente --}}
            <section class="rounded-xl border border-zinc-200 bg-white p-6">

                <h2 class="mb-4 text-lg font-semibold">
                    Seus dados
                </h2>

                <div class="flex flex-col gap-4">

                    <div>
                        <label
                            for="customer_name"
                            class="mb-1 block text-sm font-medium"
                        >
                            Nome
                        </label>

                        <input
                            id="customer_name"
                            name="customer_name"
                            type="text"
                            required
                            class="w-full rounded-lg border border-zinc-300 px-3 py-2"
                            placeholder="Seu nome"
                        >
                    </div>

                </div>

            </section>


            {{-- Mesa --}}
            <section class="rounded-xl border border-zinc-200 bg-white p-6">

                <h2 class="mb-4 text-lg font-semibold">
                    Mesa
                </h2>

                @if ($tables->isEmpty())

                    <p class="text-sm text-zinc-500">
                        Não existem mesas disponíveis no momento.
                    </p>

                @else

                    <select
                        name="table_id"
                        required
                        class="w-full rounded-lg border border-zinc-300 px-3 py-2"
                    >

                        <option value="">
                            Selecione sua mesa
                        </option>
                        @foreach ($tables as $table)
                            <option
                                value="{{ $table->id }}"
                                @selected(
                                    $selectedTable &&
                                    $selectedTable->id === $table->id
                                )
                            >
                                Mesa {{ $table->number }}
                            </option>
                        @endforeach

                    </select>

                @endif

            </section>


            {{-- Resumo --}}
            <section class="rounded-xl border border-zinc-200 bg-white p-6">

                <h2 class="mb-4 text-lg font-semibold">
                    Resumo do pedido
                </h2>

                <div
                    id="checkout-items"
                    class="flex flex-col gap-4"
                >
                </div>


                <div class="mt-6 border-t border-zinc-200 pt-4">

                    <div class="flex items-center justify-between">

                        <span class="text-lg font-semibold">
                            Total
                        </span>

                        <span
                            id="checkout-total"
                            class="text-xl font-bold"
                        >
                            R$ 0,00
                        </span>

                    </div>

                </div>

            </section>


            {{-- Botões --}}
            <div class="flex gap-3">

                <a
                    href="{{ route('menu.show', $establishment->slug) }}"
                    class="flex-1 rounded-lg border border-zinc-300 px-4 py-3 text-center font-semibold"
                >
                    Voltar
                </a>

                <button
                    type="submit"
                    class="flex-1 rounded-lg bg-black px-4 py-3 font-semibold text-white"
                >
                    Confirmar pedido
                </button>

            </div>

        </form>

    </main>


    <script>
        const cart = JSON.parse(
            sessionStorage.getItem(
                'cart-{{ $establishment->id }}'
            ) || '{}'
        );

        const products = @json($products);


        function formatPrice(value) {

            return value.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });

        }


        function renderCheckout() {

            const container =
                document.getElementById('checkout-items');

            const totalElement =
                document.getElementById('checkout-total');

            container.innerHTML = '';

            let total = 0;


            Object.entries(cart).forEach(
                ([productId, quantity]) => {

                    const product = products.find(
                        product =>
                            product.id === Number(productId)
                    );

                    if (!product) {
                        return;
                    }

                    const subtotal =
                        product.price * quantity;

                    total += subtotal;


                    const item =
                        document.createElement('div');

                    item.className =
                        'flex items-center justify-between gap-4';


                    item.innerHTML = `
                        <div>
                            <p class="font-medium">
                                ${product.name}
                            </p>

                            <p class="text-sm text-zinc-500">
                                ${quantity} ×
                                R$ ${formatPrice(product.price)}
                            </p>
                        </div>

                        <span class="font-semibold">
                            R$ ${formatPrice(subtotal)}
                        </span>
                    `;


                    container.appendChild(item);

                }
            );


            totalElement.textContent =
                `R$ ${formatPrice(total)}`;

        }


        renderCheckout();
prepareCartInputs();

        function prepareCartInputs() {

    const container =
        document.getElementById('cart-inputs');

    container.innerHTML = '';

    Object.entries(cart).forEach(
        ([productId, quantity], index) => {

            const productIdInput =
                document.createElement('input');

            productIdInput.type = 'hidden';
            productIdInput.name =
                `cart[${index}][product_id]`;
            productIdInput.value = productId;


            const quantityInput =
                document.createElement('input');

            quantityInput.type = 'hidden';
            quantityInput.name =
                `cart[${index}][quantity]`;
            quantityInput.value = quantity;


            container.appendChild(productIdInput);
            container.appendChild(quantityInput);

        }
    );

}
    </script>

</body>
</html>