<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Pedido realizado - {{ $establishment->name }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="min-h-screen bg-zinc-50">

    <main class="mx-auto max-w-2xl px-4 py-12">

        <div class="rounded-2xl border border-zinc-200 bg-white p-8">

            <div class="mb-8 text-center">

                <div class="mb-4 text-5xl">
                    ✓
                </div>

                <h1 class="text-3xl font-bold">
                    Pedido realizado!
                </h1>

                <p class="mt-2 text-zinc-500">
                    Seu pedido foi enviado para o estabelecimento.
                </p>

            </div>


            {{-- Número do pedido --}}
            <div class="mb-6 rounded-xl bg-zinc-50 p-5 text-center">

                <p class="text-sm text-zinc-500">
                    Número do pedido
                </p>

                <p class="mt-1 text-3xl font-bold">
                    #{{ $order->id }}
                </p>

            </div>


            {{-- Informações --}}
            <div class="mb-6">

                <h2 class="mb-4 text-lg font-semibold">
                    Informações
                </h2>

                <div class="flex flex-col gap-3 text-sm">

                    <div class="flex justify-between">

                        <span class="text-zinc-500">
                            Nome
                        </span>

                        <span class="font-medium">
                            {{ $order->customer_name ?? 'Cliente' }}
                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-zinc-500">
                            Mesa
                        </span>

                        <span class="font-medium">
                            Mesa {{ $order->table->number }}
                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-zinc-500">
                            Status
                        </span>

                        <span class="font-medium">
                            Pendente
                        </span>

                    </div>

                </div>

            </div>


            {{-- Produtos --}}
            <div class="border-t border-zinc-200 pt-6">

                <h2 class="mb-4 text-lg font-semibold">
                    Seu pedido
                </h2>

                <div class="flex flex-col gap-4">

                    @foreach ($order->items as $item)

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="font-medium">
                                    {{ $item->product->name }}
                                </p>

                                <p class="text-sm text-zinc-500">
                                    {{ $item->quantity }} ×
                                    R$
                                    {{ number_format(
                                        $item->unit_price,
                                        2,
                                        ',',
                                        '.'
                                    ) }}
                                </p>

                            </div>

                            <span class="font-semibold">
                                R$
                                {{ number_format(
                                    $item->subtotal,
                                    2,
                                    ',',
                                    '.'
                                ) }}
                            </span>

                        </div>

                    @endforeach

                </div>


                <div class="mt-6 flex items-center justify-between border-t border-zinc-200 pt-4">

                    <span class="text-lg font-semibold">
                        Total
                    </span>

                    <span class="text-xl font-bold">
                        R$
                        {{ number_format(
                            $order->total,
                            2,
                            ',',
                            '.'
                        ) }}
                    </span>

                </div>

            </div>


            {{-- Voltar --}}
            <div class="mt-8">

                <a
                    href="{{ route('menu.show', $establishment->slug) }}"
                    class="block rounded-lg bg-black px-4 py-3 text-center font-semibold text-white"
                >
                    Voltar ao cardápio
                </a>

            </div>

        </div>

    </main>


    <script>
        sessionStorage.removeItem(
            'cart-{{ $establishment->id }}'
        );
    </script>

</body>

</html>