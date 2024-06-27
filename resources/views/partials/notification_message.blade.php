@if(isset($message))
    <div class="flex items-center justify-center xxxmin-h-screen bg-white">
        <div class="bg-green-700 text-white p-6 m-5 rounded-lg shadow-lg">
            <p class="text-gray-700 text-center text-lg">{!! $message !!}</p>
        </div>
    </div>
@endif
