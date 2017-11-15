<?php

namespace App\Security\Command;

use Cartalyst\Sentinel\Sentinel;
use Exception;
use Symfony\Component\Console\Command\Command;

class CreateTrollCommand extends Command
{
    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * Constructor.
     *
     * @param Sentinel $sentinel
     */
    public function __construct(Sentinel $sentinel)
    {
        parent::__construct();

        $this->sentinel = $sentinel;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('troll:create')
            ->setDescription('Create new troll');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = "troll";
        $email = "troll@troll.io";
        $password = "cavernes";

        $role = $this->sentinel->findRoleByName('Admin');

        $user = $this->sentinel->registerAndActivate([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'permissions' => [
                'user.delete' => 0
            ]
        ]);

        $role->users()->attach($user);

        return 0;
    }
}
