<?php

namespace Drenso\Shared\Database\Query;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class JsonValueFunction extends FunctionNode
{
  protected Node|string $target;

  /** @var Node[] */
  protected array $paths = [];

  public function __construct(
    string $name,
    protected readonly string $functionName = 'JSON_VALUE',
  ) {
    parent::__construct($name);
  }

  public function getSql(SqlWalker $sqlWalker): string
  {
    $target = $sqlWalker->walkStringPrimary($this->target);
    $paths  = array_map($sqlWalker->walkStringPrimary(...), $this->paths);

    return sprintf('%s(%s, %s)', $this->functionName, $target, implode(', ', $paths));
  }

  public function parse(Parser $parser): void
  {
    $parser->match(TokenType::T_IDENTIFIER);
    $parser->match(TokenType::T_OPEN_PARENTHESIS);

    $this->target = $parser->StringExpression();

    $parser->match(TokenType::T_COMMA);

    $this->paths[] = $parser->StringPrimary();

    while ($parser->getLexer()->isNextToken(TokenType::T_COMMA)) {
      $parser->match(TokenType::T_COMMA);

      $this->paths[] = $parser->StringPrimary();
    }

    $parser->match(TokenType::T_CLOSE_PARENTHESIS);
  }
}
