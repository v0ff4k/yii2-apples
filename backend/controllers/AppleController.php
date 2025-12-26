<?php

namespace backend\controllers;

use backend\models\Apple;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppleController implements the custom actions for Apple model.
 */
class AppleController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Apple models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $apples = Apple::find()->all();
        return $this->render('index', compact('apples'));
    }

    /**
     * Generates a specified number of random Apple models.
     *
     * @param int $count Number of apples to generate (1–20)
     * @return \yii\web\Response
     */
    public function actionGenerate($count = 5)
    {
        $count = (int)$count;
        $count = max(1, min($count, 20)); // ограничение от 1 до 20

        for ($i = 0; $i < $count; $i++) {
            Apple::createRandom();
        }

        return $this->redirect(['index']);
    }

    /**
     * Makes an apple fall from the tree.
     *
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionFall($id)
    {
        $apple = $this->findModel($id);
        try {
            $apple->fallToGround();
        } catch (\DomainException $e) {
            \Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Eats a given percentage of an apple.
     *
     * @param int $id
     * @param float $percent
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEat($id, $percent)
    {
        $apple = $this->findModel($id);
        try {
            $apple->eat((float)$percent);
        } catch (\Exception $e) {
            \Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Apple model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * Note: This action is kept for debugging only; normally apples delete themselves when fully eaten.
     *
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Apple model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Apple the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Apple::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}