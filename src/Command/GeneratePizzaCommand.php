<?php

namespace App\Command;

use App\Entity\Ingredient;
use App\Generator\PizzaGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-pizza',
    description: 'Add a short description for your command',
)]
class GeneratePizzaCommand extends Command
{
    public function __construct(
        private PizzaGenerator $pizzaGenerator,
        private EntityManagerInterface $manager,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'If omitted, the pizza name will be randomly generated.')
            ->addOption('ingredients-count', 'i', InputOption::VALUE_OPTIONAL, 'Number of ingredients.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');

        if (!$name) {
            $faker = Factory::create('it_IT');
            $name = $faker->city;
        }
        $io->note('Pizza name will be: '.$name);

        $count = $input->getOption('ingredients-count');
        if (!is_null($count)) {
            $count = intval($count);
        } else {
            $count = random_int(1, 4);
        }

        $pizza = $this->pizzaGenerator->generate($name, $count);
        $this->manager->persist($pizza);
        $this->manager->flush();

        $io->success('You have a new pizza!');
        $io->info([
            $pizza->getName(). '('.$pizza->getId().')',
            ...array_map(fn (Ingredient $i) => $i->getName(), $pizza->getComposedWith()->toArray()),
        ]);

        return Command::SUCCESS;
    }
}
