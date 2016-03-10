<?php
namespace AppBundle\Handler;
use AppBundle\Model\UserRepository;
use AppBundle\Model\UserInterface;
use AppBundle\Form\Type\RegistrationFormType;
use AppBundle\Form\Model\RegistrationFormModel;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormError;
/**
 * ApiUserHandler.
 */
class ApiUserHandler implements ApiUserHandlerInterface
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
     * Get user from repository.
     * 
     * @param User $user
     *
     * @return User
     */
    public function get(UserInterface $user)
    {
        return $this->repository->parse($user);
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
                return $this->repository->parse($user);
            } catch (\Exception $ex) {
                //  throw new $ex;
                $form->addError(new FormError('Duplicate entry for email or username.'));
                // log this somewhere.
            }
        }
        return $form;
    }
    /**
     * Delete User.
     * 
     * @param User $user
     */
    public function delete(UserInterface $user)
    {
        $this->repository->remove($user);
    }
    /**
     * @param ProfileFormModel $userModel
     *
     * @return User
     */
    protected function insertFromForm(RegistrationFormModel $userModel)
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
     * @param User             $user
     * @param ProfileFormModel $userModel
     *
     * @return User
     */
    protected function fromForm(UserInterface $user, RegistrationFormModel $userModel)
    {
        $user
            ->setEmailCanonical($userModel->getEmail())
            ->setEmail($userModel->getEmail())
        ;
        return $user;
    }
}