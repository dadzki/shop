<?php
namespace backend\controllers;

use shop\services\auth\AuthService;
use shop\services\auth\PasswordResetService;
use shop\services\auth\VerifyEmailService;
use shop\services\ContactService;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use shop\forms\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{

    private $authService;

    /**
     * SiteController constructor.
     * @param $id
     * @param $module
     * @param AuthService $authService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        AuthService $authService,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->authService = $authService;
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'main-login';

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->authService->auth($form);
                Yii::$app->user->login($user, $form->rememberMe ? 3600 * 24 * 30 : 0);
                return $this->goBack();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        } else {
            $form->password = '';

            return $this->render('login', [
                'model' => $form,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
