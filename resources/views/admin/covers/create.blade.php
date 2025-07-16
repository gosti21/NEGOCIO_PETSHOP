<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Portadas',
        'route' => route('admin.covers.index'),
    ],
    [
        'name' => 'Crear',
    ],
]">

    <form action="{{ route('admin.covers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <x-validation-errors class="mb-4" />

        <div class="mb-4">
            <x-label class="mb-2">
                Título
            </x-label>

            <x-input name="title" value="{{ old('title') }}" class="w-full" placeholder="Ingrese el título de la portada"></x-input>
        </div>

        <div class="mb-4">
            <x-label class="mb-2">
                Fecha de inicio
            </x-label>

            <x-datepicker name="start_at" value="{{ old('start_at', now()->format('d-m-Y')) }}" placeholder="Ingrese una fecha" autocomplete="off"></x-datepicker>
        </div>

        <div class="mb-4">
            <x-label class="mb-2">
                Fecha de fin (opcional)
            </x-label>

            <x-datepicker name="end_at" value="{{ old('end_at') }}" placeholder="Ingrese una fecha" autocomplete="off"></x-datepicker>
        </div>

        <h6 class="mb-2 block font-medium text-gray-700 dark:text-gray-300">
            Imagen
        </h6>
        <div class="flex justify-center relative mt-4 mb-4">
            <div class="absolute top-3 right-0 md:right-4">
                <label class="flex items-center btn2 btn-light cursor-pointer">
                    <i class="fa-solid fa-images fa-lg mr-2"></i>
                        Subir imagen
                    <input type="file" class="hidden" accept="image/*" name="image"
                    onchange="previewImage(event, '#imgPreview')">
                </label>
            </div>
            <figure class="max-w-full">
                <img class="aspect-[3/1] max-w-full object-cover object-center"
                    <img src="https://i.ibb.co/nsX6HpT7/Captura-desde-2025-05-06-10-12-19.png" alt="Captura-desde-2025-05-06-10-12-19" border="0">
                    alt="portada" id="imgPreview">
            </figure>
            <input type="hidden" name="old_image" value="{{ old('old_image', asset('assets/img/no-image-2-horizontal.png')) }}">
        </div>

        <div class="flex justify-end">
            <x-button>
                Guardar
            </x-button>
        </div>
    </form>

    @push('js')
        <script>
            function previewImage(event, querySelector){
	            //Recuperamos el input que desencadeno la acción
	            const input = event.target;

	            //Recuperamos la etiqueta img donde cargaremos la imagen
	            $imgPreview = document.querySelector(querySelector);

                const oldImageInput = document.querySelector('input[name="old_image"]');
	            // Verificamos si existe una imagen seleccionada
	            if(!input.files.length) return

	            //Recuperamos el archivo subido
	            file = input.files[0];

	            //Creamos la url
	            objectURL = URL.createObjectURL(file);

	            //Modificamos el atributo src de la etiqueta img
	            $imgPreview.src = objectURL;

                oldImageInput.value = objectURL;

            }
        </script>
    @endpush
</x-admin-layout>
