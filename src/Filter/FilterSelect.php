<?php declare(strict_types = 1);

namespace Contributte\Datagrid\Filter;

use Contributte\Datagrid\Datagrid;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\Forms\Controls\BaseControl;
use UnexpectedValueException;

class FilterSelect extends OneColumnFilter
{

	protected bool $translateOptions = false;

	protected array $attributes = [
		'class' => ['form-select', 'form-select-sm', 'selectpicker'],
	];

	protected ?string $template = 'datagrid_filter_select.latte';

	protected ?string $type = 'select';

	protected ?string $prompt = null;

	public function __construct(
		Datagrid $grid,
		string $key,
		string $name,
		protected array $options,
		string $column
	)
	{
		parent::__construct($grid, $key, $name, $column);
	}

	public function addToFormContainer(Container $container): void
	{
		$form = $container->lookup(Form::class);

		if (!$form instanceof Form) {
			throw new UnexpectedValueException();
		}

		$translator = $form->getTranslator();

		if ($translator === null) {
			throw new UnexpectedValueException();
		}

		$select = $this->addControl($container, $this->key, $this->name, $this->options);

		if (!$this->translateOptions) {
			$select->setTranslator(null);
		}
	}

	/**
	 * @return static
	 */
	public function setTranslateOptions(bool $translateOptions = true): self
	{
		$this->translateOptions = $translateOptions;

		return $this;
	}

	public function getOptions(): array
	{
		return $this->options;
	}

	public function getTranslateOptions(): bool
	{
		return $this->translateOptions;
	}

	public function getCondition(): array
	{
		return [$this->column => $this->getValue()];
	}

	public function getPrompt(): ?string
	{
		return $this->prompt;
	}

	/**
	 * @return static
	 */
	public function setPrompt(?string $prompt): self
	{
		$this->prompt = $prompt;

		return $this;
	}

	protected function addControl(
		Container $container,
		string $key,
		string $name,
		array $options
	): BaseControl
	{
		$input = $container->addSelect($key, $name, $options);

		if ($this->getPrompt() !== null) {
			$input->setPrompt($this->getPrompt());
		}

		$this->addAttributes($input);

		return $input;
	}

}
