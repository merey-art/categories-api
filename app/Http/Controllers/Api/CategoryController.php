<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\Interfaces\CategoryServiceInterface;
use App\DTO\CreateCategoryDTO;
use App\DTO\UpdateCategoryDTO;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(private CategoryServiceInterface $service) {}

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Список категорий в древовидном виде",
     *     @OA\Response(
     *         response=200,
     *         description="Список категорий",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Category"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $tree = $this->service->getTree();
        return response()->json($tree);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Получение категории по ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Категория",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(response=404, description="Категория не найдена")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $dto = $this->service->getById($id);
        return response()->json($dto);
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Создание категории",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Категория создана", @OA\JsonContent(ref="#/components/schemas/Category")),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function store(CreateCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $dto = new CreateCategoryDTO($data['name'], $data['description'] ?? null, $data['parent_id'] ?? null);
        $created = $this->service->create($dto);
        return response()->json($created, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Обновление категории",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Категория обновлена", @OA\JsonContent(ref="#/components/schemas/Category")),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $dto = new UpdateCategoryDTO($id, $data['name'], $data['description'] ?? null, $data['parent_id'] ?? null);
        $updated = $this->service->update($dto);
        return response()->json($updated);
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Удаление категории",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Категория удалена"),
     *     @OA\Response(response=404, description="Категория не найдена")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}
