<?php

declare(strict_types=1);

namespace backend\controllers;

use common\models\Apple;
use DomainException;
use InvalidArgumentException;
use yii\filters\VerbFilter;
use yii\log\Logger;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AppleController extends BaseController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex(): string
    {
        $apples = Apple::find()->all();

        return $this->render('index', compact('apples'));
    }

    public function actionGenerate(int $count = 5): Response
    {
        $count = max(1, min($count, 20));

        for ($i = 0; $i < $count; $i++) {
            try {
                Apple::createRandom();
            } catch (\RuntimeException $e) {
                $this->app()->session->setFlash('error', $e->getMessage());

                break;
            }
        }

        return $this->redirect(['index']);
    }

    public function actionFall(int $id): Response
    {
        $apple = $this->findModel($id);
        $isAjax = $this->app()->request->isAjax;

        try {
            $apple->fallToGround();
            $this->logAction('fall', $id);

            if ($isAjax) {
                $this->app()->response->format = Response::FORMAT_JSON;

                return $this->asJson([
                    'success' => true,
                    'message' => 'Яблоко упало',
                    'status' => $apple->getStatus(),
                    'fell_at' => $apple->fell_at,
                ]);
            }

            $this->app()->session->setFlash('success', 'Яблоко упало');
        } catch (DomainException $e) {
            if ($isAjax) {
                $this->app()->response->format = Response::FORMAT_JSON;

                return $this->asJson(['success' => false, 'message' => $e->getMessage()]);
            }
            $this->app()->session->setFlash('error', $e->getMessage());
        } catch (\Throwable $e) {
            $this->app()->errorHandler->logException($e);
            if ($isAjax) {
                $this->app()->response->format = Response::FORMAT_JSON;

                return $this->asJson(['success' => false, 'message' => 'Произошла ошибка при падении яблока']);
            }
            $this->app()->session->setFlash('error', 'Произошла ошибка при падении яблока');
        }

        return $this->redirect(['index']);
    }

    public function actionEat(int $id, float $percent): Response
    {
        $apple = $this->findModel($id);
        $isAjax = $this->app()->request->isAjax;

        try {
            $maxPercent = 100 - $apple->eaten_percent;
            if ($percent < 1 || $percent > $maxPercent) {
                throw new InvalidArgumentException("Процент должен быть от 1 до {$maxPercent}");
            }

            $apple->eat($percent);
            $this->logAction('eat', $id, ['percent' => $percent]);

            if ($isAjax) {
                $this->app()->response->format = Response::FORMAT_JSON;

                return $this->asJson([
                    'success' => true,
                    'message' => sprintf('Съедено %.0f%% яблока', $percent),
                    'eaten_percent' => $apple->eaten_percent,
                    'deleted' => $apple->eaten_percent >= 100,
                ]);
            }

            $this->app()->session->setFlash('success', sprintf('Съедено %.0f%% яблока', $percent));
        } catch (DomainException|InvalidArgumentException $e) {
            if ($isAjax) {
                $this->app()->response->format = Response::FORMAT_JSON;

                return $this->asJson(['success' => false, 'message' => $e->getMessage()]);
            }
            $this->app()->session->setFlash('error', $e->getMessage());
        } catch (\Throwable $e) {
            $this->app()->errorHandler->logException($e);
            if ($isAjax) {
                $this->app()->response->format = Response::FORMAT_JSON;

                return $this->asJson(['success' => false, 'message' => 'Произошла ошибка при поедании яблока']);
            }
            $this->app()->session->setFlash('error', 'Произошла ошибка при поедании яблока');
        }

        return $this->redirect(['index']);
    }

    public function actionDelete(int $id): Response
    {
        try {
            $this->findModel($id)->delete();
            $this->app()->session->setFlash('success', 'Яблоко удалено');
        } catch (\Throwable $e) {
            $this->app()->session->setFlash('error', 'Не удалось удалить яблоко');
        }

        return $this->redirect(['index']);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function logAction(string $action, int $appleId, array $data = []): void
    {
        $userId = (string) ($this->app()->user->id ?? 'guest');
        $message = sprintf(
            'User %s performed action "%s" on apple #%d at %s. Data: %s',
            $userId,
            $action,
            $appleId,
            date('Y-m-d H:i:s'),
            json_encode($data)
        );

        $this->app()->log->logger->log($message, Logger::LEVEL_INFO, 'apple_actions');
    }

    protected function findModel(int $id): Apple
    {
        $model = Apple::findOne(['id' => $id]);
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
