{assign var="smarty_referer" value=$smarty.server.HTTP_REFERER}
{insert name="header" content="Location: $smarty_referer"}
