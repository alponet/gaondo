<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    /**
     * @Route("/i18n/")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function i18nAction()
    {
        $i18n = $this->get("translator");

        $translations = [
            "main" => [
                "login"     => $i18n->trans("main.login"),
                'logout'    => $i18n->trans('main.logout'),
                "register"  => $i18n->trans("main.register"),
                "upload"    => $i18n->trans("main.upload"),
                "cancel"    => $i18n->trans("main.cancel"),
                "submit"    => $i18n->trans("main.submit"),
            ],
            "meme" => [
                "description"   => $i18n->trans("meme.description"),
                "file"          => $i18n->trans("meme.file"),
                "newMeme"       => $i18n->trans("meme.newMeme"),
                "title"         => $i18n->trans("meme.title"),
            ],
            "profile" => [
                "changePassword"    => $i18n->trans("profile.changePassword"),
                "oldPassword"       => $i18n->trans("profile.oldPassword"),
                "newPassword"       => $i18n->trans("profile.newPassword"),
                "verifyNewPassword" => $i18n->trans("profile.verifyNewPassword"),
                "passwordChanged"   => $i18n->trans("profile.passwordChanged"),
                "deleteAccount"     => $i18n->trans("profile.deleteAccount"),
                'reallyDeleteAcc'   => $i18n->trans("profile.reallyDeleteAcc"),
                'notReversible'     => $i18n->trans("profile.notReversible"),
                'passwordToConfirm' => $i18n->trans("profile.passwordToConfirm"),
                'accDeleted'        => $i18n->trans("profile.accDeleted"),
            ],
            'register' => [
                'name'     => $i18n->trans('register.name'),
                'email'    => $i18n->trans('register.email'),
                'register' => $i18n->trans('register.register'),
            ],
            "status" => [
                "uploading" => $i18n->trans("status.uploading"),
                'loading'   => $i18n->trans('status.loading'),
            ]
        ];


        return $this->json($translations);
    }
}
