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
        'name' => 'Editar - ' . $data->title,
    ],
]">

    <form action="{{ route('admin.covers.update', $data) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <x-validation-errors class="mb-4" />
        
        <div class="mb-4">
            <x-label class="mb-2">
                Título
            </x-label>

            <x-input name="title" value="{{ old('title', $data->title) }}" class="w-full" placeholder="Ingrese el título de la portada"></x-input>
        </div>

        <div class="mb-4">
            <x-label class="mb-2">
                Fecha de inicio
            </x-label>

            <x-datepicker name="start_at" value="{{ old('start_at', $data->start_at->format('d-m-Y')) }}" placeholder="Ingrese una fecha" autocomplete="off"></x-datepicker>
        </div>
        
        <div class="mb-4">
            <x-label class="mb-2">
                Fecha de fin (opcional)
            </x-label>

            <x-datepicker name="end_at" value="{{ old('end_at', $data->end_at ? $data->end_at->format('d-m-Y') : '') }}" placeholder="Ingrese una fecha" autocomplete="off"></x-datepicker>
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
                    src="{{ old('old_image', Storage::url($data->images->first()->path)) }}" 
                    alt="portada" id="imgPreview">
            </figure>
            <input type="hidden" name="old_image" value="{{ old('old_image', Storage::url($data->images->first()->path)) }}">
        </div>

        <div class="mb-4">
            <h6 class="mb-2 block font-medium text-gray-700 dark:text-gray-300">
                Estado
            </h6>
            <div class="flex items-center mb-4">
                <x-radio id="radio-1" name="is_active" value="1" :checked="$data->is_active == 1"></x-radio>
                <x-label for="radio-1" class="ms-2">Activo</x-label>
            </div>
            
            <div class="flex items-center">
                <x-radio id="radio-2" name="is_active" value="0" :checked="$data->is_active == 0"></x-radio>
                <x-label for="radio-2" class="ms-2">Inactivo</x-label>
            </div>
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