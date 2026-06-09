<?php

declare(strict_types=1);

namespace backend\controllers\api;

use common\models\Apple;
use DomainException;
use InvalidArgumentException;
use yii\rest\Controller;
use yii\web\Application;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;

class AppleController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);

        return $behaviors;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function actionIndex(): array
    {
        $apples = Apple::find()->all();

        return array_map(fn (Apple $apple): array => $this->serializeApple($apple), $apples);
    }

    /**
     * @return array<string, mixed>
     */
    public function actionView(int $id): array
    {
        return $this->serializeApple($this->findModel($id));
    }

    /**
     * @return array<string, mixed>
     */
    public function actionCreate(): array
    {
        /** @var Application $app */
        $app = \Yii::$app;

        try {
            $apple = Apple::createRandom();
            $app->response->setStatusCode(201);

            return $this->serializeApple($apple);
        } catch (\RuntimeException $e) {
            $app->response->setStatusCode(400);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function actionFall(int $id): array
    {
        /** @var Application $app */
        $app = \Yii::$app;
        $apple = $this->findModel($id);

        try {
            $apple->fallToGround();

            return ['success' => true, 'apple' => $this->serializeApple($apple)];
        } catch (DomainException $e) {
            $app->response->setStatusCode(400);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function actionEat(int $id): array
    {
        /** @var Application $app */
        $app = \Yii::$app;
        /** @var Request $request */
        $request = $app->request;
        $apple = $this->findModel($id);
        $percent = (float) $request->post('percent', 10);

        try {
            $apple->eat($percent);

            return [
                'success' => true,
                'eaten_percent' => $apple->eaten_percent,
                'deleted' => $apple->eaten_percent >= 100,
            ];
        } catch (DomainException|InvalidArgumentException $e) {
            $app->response->setStatusCode(400);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function actionDelete(int $id): array
    {
        /** @var Application $app */
        $app = \Yii::$app;
        $apple = $this->findModel($id);

        try {
            $apple->delete();

            return ['success' => true, 'message' => 'Apple deleted'];
        } catch (\Throwable $e) {
            $app->response->setStatusCode(400);

            return ['success' => false, 'message' => 'Failed to delete apple'];
        }
    }

    /**
     * @return array{id: int|null, color: string, status: string, status_label: string, eaten_percent: float, created_at: int, fell_at: int|null, can_eat: bool, is_rotten: bool}
     */
    private function serializeApple(Apple $apple): array
    {
        return [
            'id' => $apple->id,
            'color' => $apple->color,
            'status' => $apple->getStatus(),
            'status_label' => match ($apple->getStatus()) {
                Apple::STATUS_ON_TREE => 'Висит на дереве',
                Apple::STATUS_ON_GROUND => 'Упало',
                Apple::STATUS_ROTTEN => 'Гнилое',
                default => 'Неизвестно',
            },
            'eaten_percent' => (float) $apple->eaten_percent,
            'created_at' => $apple->created_at,
            'fell_at' => $apple->fell_at,
            'can_eat' => $apple->canEat(),
            'is_rotten' => $apple->isRotten(),
        ];
    }

    private function findModel(int $id): Apple
    {
        $model = Apple::findOne(['id' => $id]);
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Apple not found');
    }
}
