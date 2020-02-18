# PHP GraphQL Client

A GraphQL client written in PHP which provides very simple, yet powerful, query
generator classes that make the process of interacting with a GraphQL server a
very simple one.

# Usage

There are 3 primary ways to use this package to generate your GraphQL queries:

1. Query Class: Simple class that maps to GraphQL queries. It's designed to
   manipulate queries with ease and speed.
2. QueryBuilder Class: Builder class that can be used to generate `Query`
   objects dynamically. It's design to be used in cases where a query is being
   build in a dynamic fashion.
3. PHP GraphQL-OQM: An extension to this package. It Eliminates the need to
   write any GraphQL queries or refer to the API documentation or syntax. It
   generates query objects from the API schema, declaration exposed through
   GraphQL's introspection, which can then be simply interacted with.
