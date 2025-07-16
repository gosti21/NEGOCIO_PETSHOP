<div class="relative max-w-full">
  <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
    <span class="text-gray-500 dark:text-gray-400" aria-hidden="true">
        <i class="fa-solid fa-calendar"></i>
    </span>
  </div>
  <input {!! $attributes->merge(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-600  dark:focus:ring-indigo-600']) !!} 
    datepicker datepicker-autohide datepicker-format="dd-mm-yyyy" type="text">
</div>