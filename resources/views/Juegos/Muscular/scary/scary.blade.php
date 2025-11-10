<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Scary Witch Typing | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('CSS/Juegos/Muscular/scary/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="burbujas">
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
    </div>
    <header class="w-full bg-white py-2 px-5 flex items-center justify-between shadow-md z-10">
            <h1 class="logo">Memoria Ecoica</h1>
        <a href="/TiposMemoria/Mmuscular" class="text-gray-600 hover:text-[#56e7c3] flex items-center gap-2 font-semibold transition">
            <span class="text-xl">&larr;</span> Volver
        </a>
    </header>
    <main class="flex flex-col items-center justify-center min-h-[calc(100vh-160px)] px-4 md:px-0 relative z-10">
        <div class="w-full max-w-5xl mx-auto flex flex-col items-center">
            <div class="w-full flex flex-row items-center justify-center gap-8 mt-2 mb-3">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-[#4568DC] mb-1">Scary Witch Typing</h2>
                    <p class="text-base text-gray-600">Teclado, agilidad y memoria muscular</p>
                </div>
                <div class="bg-white bg-opacity-90 rounded-full px-8 py-3 shadow text-[#56e7c3] font-bold text-xl tracking-tight border border-[#56e7c3] select-none">
                    Score: <span id="score">0</span>
                </div>
            </div>
            <div class="game-card bg-white rounded-2xl shadow-lg flex flex-col items-center justify-center w-full relative"
                 style="min-height: 72vh; width: 11000%;">
                <button id="pauseBtn"
                        class="absolute top-6 right-8 bg-[#ffb347] hover:bg-[#ffa500] text-white px-6 py-2 rounded-full font-semibold text-lg shadow transition z-30"
                        style="outline:none;">
                    Pausa
                </button>
                <div class="w-full flex justify-center items-center px-1 pb-6">
                    <canvas id="scaryCanvas"
                        class="rounded-xl shadow-lg"
                        style="min-height: 64vh;  width:95vw; max-width:1800px; height:60vh; background:#d6fff6; border:2px solid #56e7c3; display:block;"></canvas>
                </div>
            </div>
        </div>
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-30" id="modal" style="display:flex;">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">
                <h1 class="text-4xl font-bold leading-none text-[#56e7c3]" id="scoreH1">0</h1>
                <p class="text-base text-gray-700 mb-2 font-semibold">Puntos</p>
                <p class="text-base text-gray-700 mb-6">¿PREPARADO?</p>
                <button class="bg-indigo-500 text-white w-full py-3 rounded-full text-sm" id="startGameBtn">Empezar</button>                <a href="/TiposMemoria/Mmuscular" class="block w-full mt-2 text-[#56e7c3] font-semibold hover:underline">Volver</a>
            </div>
        </div>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('JS/Juegos/Muscular/scary/app.js') }}"></script>
</body>
</html>
