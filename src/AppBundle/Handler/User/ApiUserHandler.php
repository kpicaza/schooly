<?php

namespace AppBundle\Handler\User;

use AppBundle\Model\User\UserRepository;
use AppBundle\Model\User\UserInterface;
use AppBundle\Form\Type\RegistrationFormType;
use AppBundle\Form\Model\RegistrationFormModel;
use AppBundle\Form\Type\ProfileFormType;
use AppBundle\Form\Model\ProfileFormModel;
use AppBundle\Form\Model\UserFormModelInterface;
use AppBundle\Handler\ApiHandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormError;

/**
 * ApiUserHandler.
 */
class ApiUserHandler implements ApiHandlerInterface
{
    /**
     * @var UserRepository
     */
    protected $repository;
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;
    /**
     * Init Handler.
     * 
     * @param UserRepository       $repository
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(UserRepository $repository, FormFactoryInterface $formFactory)
    {
        $this->repository = $repository;
        $this->formFactory = $formFactory;
    }
    /**
     * Get object list from repository.
     * 
     * @param array $criteria
     * @param array $sort
     * @param int   $limit
     * @param int   $skip
     */
    public function getList(array $criteria, array $sort = null, $limit = null, $skip = null)
    {
        return $this->repository->findBy($criteria, $sort, $limit, $skip);
    }
    /**
     * Get user from repository.
     * 
     * @param int $id
     *
     * @return User
     */
    public function get($id)
    {
        return $this->repository->parse($id);
    }
    /**
     * Insert User to repository.
     * 
     * @param array $params
     *
     * @return User
     */
    public function post(array $params)
    {
        $userModel = RegistrationFormModel::fromArray($params);
        $form = $this->formFactory->create(RegistrationFormType::class, $userModel, array('method' => 'POST'));
        $form->submit($params);
        if ($form->isValid()) {
            try {
                $rawUser = $this->insertFromForm($form->getData());
                $user = $this->repository->insert($rawUser);

                return $this->repository->parse($user->getId());
            } catch (\Exception $ex) {
                // throw new $ex;
                $form->addError(new FormError('Duplicate entry for email or username.'));
                // log this somewhere.
            }
        }

        return $form;
    }
    /**
     * Update User to repository.
     * 
     * @param array $params
     *
     * @return type
     */
    public function put($id, array $params)
    {
        $userModel = ProfileFormModel::fromArray($params);

        $form = $this->formFactory->create(ProfileFormType::class, $userModel, array('method' => 'POST'));
        $form->submit($params);

        if ($form->isValid()) {
            $user = $this->updateFromForm($id, $form->getData());

            $this->repository->update();

            return $this->repository->parse($user->getId());
        }

        return $form;
    }
    /**
     * Delete User.
     * 
     * @param $id
     */
    public function delete($id)
    {
        $this->repository->remove($id);
    }
    /**
     * @param ProfileFormModel $userModel
     *
     * @return User
     */
    protected function insertFromForm(UserFormModelInterface $userModel)
    {
        $user = $this->repository->findNew();
        $user
            ->setUsername($userModel->getUsername())
            ->setUsernameCanonical($userModel->getUsername())
            ->setPlainPassword($userModel->getPlainPassword())
        ;

        return $this->fromForm($user, $userModel);
    }
    /**
     * @param type             $id
     * @param ProfileFormModel $userModel
     *
     * @return User
     */
    protected function updateFromForm($id, UserFormModelInterface $userModel)
    {
        $user = $this->repository->find($id);

        $user->setDescription($userModel->getDescription());

        return $this->fromForm($user, $userModel);
    }
    /**
     * @param User             $user
     * @param ProfileFormModel $userModel
     *
     * @return User
     */
    protected function fromForm(UserInterface $user, UserFormModelInterface $userModel)
    {
        $user
            ->setEmailCanonical($userModel->getEmail())
            ->setEmail($userModel->getEmail())
        ;

        return $user;
    }
}
