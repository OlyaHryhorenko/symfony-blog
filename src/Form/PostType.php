<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class PostType extends AbstractType {

	/**
	 * @var Security
	 */
	private $security;

	public function __construct( Security $security ) {
		$this->security = $security;
	}

	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'title', TextType::class, [ 'help' => 'Enter your title' ] )
			->add( 'content', TextareaType::class, [ 'help' => 'Enter your content' ] )
			->add( 'slug', TextType::class, [ 'help' => 'Enter your slug' ] )
			->add( 'thumbnail', FileType::class, [
				'label'    => 'Thumbnail',
				'mapped'   => false,
				'required' => false
			] )
			->add( 'categories', EntityType::class, array(
				'class'        => 'App\Entity\Category',
				'choice_label' => 'name',
				'multiple'     => true
			) )
			->add( 'submit', SubmitType::class );
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => Post::class
		] );
	}


}