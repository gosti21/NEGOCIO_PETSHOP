<?php

namespace App\Traits\Admin;

use Illuminate\Http\Request;

trait resolvesRequests
{
    /**
     * Resuelve dinámicamente el StoreRequest del modelo.
     */
    protected function resolveStoreRequest(): string
    {
        $modelName = class_basename($this->model);
        return "App\\Http\\Requests\\Admin\\{$modelName}\\Store{$modelName}Request";
    }

    /**
     * Resuelve dinámicamente el UpdateRequest del modelo.
     */
    protected function resolveUpdateRequest(): string
    {
        $modelName = class_basename($this->model);
        return "App\\Http\\Requests\\Admin\\{$modelName}\\Update{$modelName}Request";
    }

    /**
     * Valida y procesa el StoreRequest.
     */
    protected function validateStoreRequest(Request $request): array
    {
        $storeRequestClass = $this->resolveStoreRequest();

        $storeRequest = app($storeRequestClass);
        $storeRequest->setContainer(app())->setRedirector(app('redirect'));
        $storeRequest->validateResolved();

        return $storeRequest->validated();
    }

    /**
     * Valida y procesa el UpdateRequest.
     */
    protected function validateUpdateRequest(Request $request): array
    {
        $updateRequestClass = $this->resolveUpdateRequest();

        $updateRequest = app($updateRequestClass);
        $updateRequest->setContainer(app())->setRedirector(app('redirect'));
        $updateRequest->validateResolved();

        return $updateRequest->validated();
    }
}
