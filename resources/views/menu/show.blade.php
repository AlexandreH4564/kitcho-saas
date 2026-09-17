<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $establishment->name }} - Cardápio</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-zinc-50">

    <main class="mx-auto max-w-4xl px-4 py-8">

        {{-- Cabeçalho --}}
        <header class="mb-8 text-center">

            <h1 class="text-3xl font-bold">
                {{ $establishment->name }}
            </h1>

            <p class="mt-2 text-sm text-zinc-500">
                {{ $establishment->address }}
            </p>

        </header>

        {{-- Cardápio --}}
        <div id="menu-container" class="flex flex-col gap-8">

            @forelse ($categories as $category)

                @if ($category->products->isNotEmpty())

                    <section>

                        <h2 class="mb-4 text-2xl font-semibold">
                            {{ $category->name }}
                        </h2>

                        <div class="flex flex-col gap-3">

                            @foreach ($category->products as $product)

                                <article
                                    class="rounded-xl border border-zinc-200 bg-white p-5"
                                >

                                    <div class="flex items-start justify-between gap-4">

                                        <div class="min-w-0">

                                            <h3 class="font-semibold">
                                                {{ $product->name }}
                                            </h3>

                                            @if ($product->description)
                                                <p class="mt-1 text-sm text-zinc-500">
                                                    {{ $product->description }}
                                                </p>
                                            @endif

                                            <p class="mt-2 font-semibold">
                                                R$
                                                {{ number_format($product->price, 2, ',', '.') }}
                                            </p>

                                        </div>

                                        {{-- Quantidade --}}
                                        <div class="flex shrink-0 items-center gap-2">

                                            <button
                                                type="button"
                                                onclick="decreaseQuantity({{ $product->id }})"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-300"
                                            >
                                                −
                                            </button>

                                            <span
                                                id="quantity-{{ $product->id }}"
                                                class="w-6 text-center"
                                            >
                                                0
                                            </span>

                                            <button
                                                type="button"
                                                onclick="increaseQuantity({{ $product->id }})"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-300"
                                            >
                                                +
                                            </button>

                                        </div>

                                    </div>

                                </article>

                            @endforeach

                        </div>

                    </section>

                @endif

            @empty

                <div class="rounded-xl border border-zinc-200 bg-white p-8 text-center">

                    <h2 class="text-lg font-semibold">
                        Cardápio indisponível
                    </h2>

                    <p class="mt-2 text-sm text-zinc-500">
                        Este estabelecimento ainda não possui produtos cadastrados.
                    </p>

                </div>

            @endforelse

        </div>

    </main>


    {{-- Barra do carrinho --}}
    <div
        id="cart-bar"
        class="fixed bottom-0 left-0 right-0 hidden border-t border-zinc-200 bg-white p-4 shadow-lg"
    >

        <div class="mx-auto flex max-w-4xl items-center justify-between gap-4">

            <div>
                <p class="text-sm text-zinc-500">
                    Seu pedido
                </p>

                <p class="font-semibold">
                    <span id="cart-items">0</span>
                    item(ns)
                </p>
            </div>

            <button
                type="button"
                onclick="showCart()"
                class="rounded-lg bg-black px-5 py-3 font-semibold text-white"
            >
                Ver carrinho
            </button>

        </div>

    </div>


    {{-- Modal do carrinho --}}
    <div
        id="cart-modal"
        class="fixed inset-0 z-50 hidden bg-black/50 p-4"
    >

        <div class="mx-auto flex min-h-full max-w-lg items-center justify-center">

            <div class="w-full rounded-2xl bg-white p-6 shadow-xl">

                {{-- Cabeçalho --}}
                <div class="mb-6 flex items-center justify-between">

                    <div>
                        <h2 class="text-2xl font-bold">
                            Seu carrinho
                        </h2>

                        <p class="text-sm text-zinc-500">
                            {{ $establishment->name }}
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="closeCart()"
                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-300"
                    >
                        ×
                    </button>

                </div>


                {{-- Itens --}}
                <div
                    id="cart-content"
                    class="flex max-h-[60vh] flex-col gap-4 overflow-y-auto"
                >
                </div>


                {{-- Total --}}
                <div class="mt-6 border-t border-zinc-200 pt-4">

                    <div class="flex items-center justify-between">

                        <span class="text-lg font-semibold">
                            Total
                        </span>

                        <span
                            id="cart-total"
                            class="text-xl font-bold"
                        >
                            R$ 0,00
                        </span>

                    </div>

                </div>


                {{-- Ações --}}
                <div class="mt-6 flex gap-3">

                    <button
                        type="button"
                        onclick="closeCart()"
                        class="flex-1 rounded-lg border border-zinc-300 px-4 py-3 font-semibold"
                    >
                        Voltar
                    </button>

                    <button
                        type="button"
                        class="flex-1 rounded-lg bg-black px-4 py-3 font-semibold text-white"
                        onclick="continueOrder()"
                    >
                        Continuar
                    </button>

                </div>

            </div>

        </div>

    </div>


    <script>
const products = @json($products);

        const cart = {};


        function increaseQuantity(productId) {

            cart[productId] = (cart[productId] || 0) + 1;

            updateQuantity(productId);
            updateCart();

        }


        function decreaseQuantity(productId) {

            if (!cart[productId]) {
                return;
            }

            cart[productId]--;

            if (cart[productId] <= 0) {
                delete cart[productId];
            }

            updateQuantity(productId);
            updateCart();

        }


        function updateQuantity(productId) {

            const element = document.getElementById(
                `quantity-${productId}`
            );

            if (!element) {
                return;
            }

            element.textContent = cart[productId] || 0;

        }


        function updateCart() {

            const totalItems = Object.values(cart)
                .reduce((total, quantity) => total + quantity, 0);
                sessionStorage.setItem(
    'cart-{{ $establishment->id }}',
    JSON.stringify(cart)
);

            const cartBar = document.getElementById('cart-bar');
            const cartItems = document.getElementById('cart-items');

            cartItems.textContent = totalItems;

            if (totalItems > 0) {
                cartBar.classList.remove('hidden');
            } else {
                cartBar.classList.add('hidden');
            }

        }


        function showCart() {

            renderCart();

            const modal = document.getElementById('cart-modal');

            modal.classList.remove('hidden');

        }


        function closeCart() {

            const modal = document.getElementById('cart-modal');

            modal.classList.add('hidden');

        }


        function renderCart() {

            const content = document.getElementById('cart-content');
            const totalElement = document.getElementById('cart-total');

            content.innerHTML = '';

            let total = 0;

            Object.entries(cart).forEach(([productId, quantity]) => {

                const product = products.find(
                    product => product.id === Number(productId)
                );

                if (!product) {
                    return;
                }

                const subtotal = product.price * quantity;

                total += subtotal;

                const item = document.createElement('div');

                item.className =
                    'flex items-center justify-between gap-4 border-b border-zinc-100 pb-4';

                item.innerHTML = `
                    <div class="min-w-0">
                        <p class="font-semibold">
                            ${product.name}
                        </p>

                        <p class="text-sm text-zinc-500">
                            R$ ${formatPrice(product.price)} cada
                        </p>
                    </div>

                    <div class="flex items-center gap-3">

                        <button
                            type="button"
                            onclick="decreaseFromCart(${product.id})"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-300"
                        >
                            −
                        </button>

                        <span class="w-5 text-center">
                            ${quantity}
                        </span>

                        <button
                            type="button"
                            onclick="increaseFromCart(${product.id})"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-300"
                        >
                            +
                        </button>

                    </div>

                    <span class="shrink-0 font-semibold">
                        R$ ${formatPrice(subtotal)}
                    </span>
                `;

                content.appendChild(item);

            });


            if (Object.keys(cart).length === 0) {

                content.innerHTML = `
                    <div class="py-8 text-center">
                        <p class="text-zinc-500">
                            Seu carrinho está vazio.
                        </p>
                    </div>
                `;

            }


            totalElement.textContent = `R$ ${formatPrice(total)}`;

        }


        function increaseFromCart(productId) {

            increaseQuantity(productId);

            renderCart();

        }


        function decreaseFromCart(productId) {

            decreaseQuantity(productId);

            if (Object.keys(cart).length === 0) {
                closeCart();
                return;
            }

            renderCart();

        }


        function formatPrice(value) {

            return value.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });

        }


        function continueOrder() {
        if (Object.keys(cart).length === 0) {
            return;
        }

        const checkoutUrl = new URL(
            "{{ route('menu.checkout', $establishment->slug) }}",
            window.location.origin
        );

        @if ($table)
            checkoutUrl.searchParams.set(
                'table',
                '{{ $table->id }}'
            );
        @endif

        window.location.href = checkoutUrl.toString();
    }


    </script>

</body>
</html>