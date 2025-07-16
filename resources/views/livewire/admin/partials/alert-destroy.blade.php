@push('js')
    <script>
        function confirmDelete(modelId, method, modelId2 = ''){
            Swal.fire({
                title: "¿Estas seguro?",
                text: "¡No podrás revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    if(modelId2 == ''){
                        @this.call(method, modelId)
                    }else{
                        @this.call(method, modelId, modelId2)
                    }
                }
            });
        }
    </script>
@endpush