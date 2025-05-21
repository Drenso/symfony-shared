<?php

namespace Drenso\Shared\Database\Query;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;
use Drenso\Shared\Exception\NullGuard\MustNotBeNullException;

/**
 * Provides a way to access an entity's discriminator field in DQL
 * queries.
 *
 * Assuming the same "Person" entity from Doctrine's documentation on
 * Inheritence Mapping, which has a discriminator field named "discr":
 *
 * Using the TYPE() function, DQL will interpret this:
 *
 * <pre>'SELECT TYPE(p) FROM Person p'</pre>
 *
 * as if you had written this:
 *
 * <pre>'SELECT p.discr FROM Person p'</pre>
 *
 * This conversion happens at the SQL level, so the ORM is no longer
 * part of the picture at that point.
 *
 * Normally, if you try to access the discriminator field in a DQL
 * Query, Doctrine will complain that the field does not exist on the
 * entity. This makes sense from an ORM point-of-view, but having
 * access to the discriminator field allows us to, for example:
 *
 * - get the type when we only have an ID
 * - query within a subset of all the available types
 *
 * Source: https://gist.github.com/jasonhofer/8420677
 */
class DiscriminatorTypeFunction extends FunctionNode
{
  public string $dqlAlias;

  /** @throws QueryException */
  public function getSql(SqlWalker $sqlWalker): string
  {
    $qComp = $sqlWalker->getQueryComponent($this->dqlAlias);
    /** @var ClassMetadata<object> $class */
    $class      = $qComp['metadata'] ?? throw new MustNotBeNullException();
    $tableAlias = $sqlWalker->getSQLTableAlias($class->getTableName(), $this->dqlAlias);

    if (!isset($class->discriminatorColumn['name'])) {
      throw QueryException::semanticalError(
        'Discriminator type only supports entities with a discriminator column.',
      );
    }

    return $tableAlias . '.' . $class->discriminatorColumn['name'];
  }

  /**
   * @throws QueryException
   *
   * @phan-suppress PhanTypeMismatchArgumentProbablyReal
   */
  public function parse(Parser $parser): void
  {
    $parser->match(TokenType::T_IDENTIFIER);
    $parser->match(TokenType::T_OPEN_PARENTHESIS);

    $this->dqlAlias = $parser->IdentificationVariable();

    $parser->match(TokenType::T_CLOSE_PARENTHESIS);
  }
}
