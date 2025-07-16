<td class="px-6 py-4 text-right">
    <div class="flex justify-between">
        <a href="{{ route($showRoute, $item) }}" class="font-medium text-green-600 dark:text-green-500">
            <i class="fa-solid fa-eye fa-lg"></i>
        </a>
        <a href="{{ route($editRoute, $item) }}" class="font-medium text-yellow-600 dark:text-yellow-500">
            <i class="fa-solid fa-pen-to-square fa-lg"></i>
        </a>
        <form action="{{ route($deleteRoute, $item) }}" method="POST" id="delete-form-{{ $item->id }}">
            @csrf
            @method('DELETE')
            <button type="button" class="font-medium text-red-600 dark:text-red-500"
                onclick="confirmDelete({{ $item->id }})">
                <i class="fa-solid fa-trash-can fa-lg"></i>
            </button>
        </form>
    </div>
</td>
