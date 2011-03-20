<div id="auth_login_zone">
{if $failed}
<p>{@jauth~auth.failedToLogin@}</p>
{/if}

{if ! $isLogged}

<form action="{formurl 'jauth~login:in'}" method="post" id="loginForm">
      <fieldset>
      <table>
       <tr>
           <th><label for="login">Login</label></th>
        <td><input type="text" name="login" id="login" size="15" value="{$login}" /></td>
       </tr>
       <tr>
           <th><label for="password">Mot de passe</label></th>
        <td><input type="password" name="password" id="password" size="15" /></td>
       </tr>
       {if $showRememberMe}
       <tr>
           <th><label for="rememberMe">{@jauth~auth.rememberMe@}</label></th>
        <td><input type="checkbox" name="rememberMe" id="rememberMe" value="1" /></td>
       </tr>
       {/if}
       </table>
       {formurlparam 'jauth~login:in'}
       {if !empty($auth_url_return)}
       <input type="hidden" name="auth_url_return" value="{$auth_url_return|eschtml}" />
       {/if}
       <input type="submit" value="Connexion"/>
       </fieldset>
   </form>
{else}
    <p>{$user->login} | <a href="{jurl 'jauth~login:out'}" >Déconnexion</a></p>
{/if}
</div>
