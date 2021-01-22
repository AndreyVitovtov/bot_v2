Installation
<br>
1). In the terminal, execute the commands:
<br>
    &nbsp;&nbsp;&nbsp;&nbsp;`composer require`
<br>
    &nbsp;&nbsp;&nbsp;&nbsp;`npm install`
<br>     
2). Create env file from env.example
<br>  
3). if necessary, execute the command in the terminal:
    &nbsp;&nbsp;&nbsp;&nbsp;`php artisan key:generate`
<br>
<br>
4). Go to addresses:
<br>
    &nbsp;&nbsp;&nbsp;&nbsp;`https://you_domain/migrate`
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;`https://you_domain/seed`  
&nbsp;&nbsp;&nbsp;&nbsp;Or execute commands in terminal:  
    &nbsp;&nbsp;&nbsp;&nbsp;`php artisan migrate`  
    &nbsp;&nbsp;&nbsp;&nbsp;`php artisan db:seed`  
<br>
5). Add to cron task scheduler ``` * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1 ```

Development
<br>
Developer panel: https://you_domain/developer
<br>
Admin panel: https://you_domain/admin
<br>
Authorization data:  
    &nbsp;&nbsp;&nbsp;&nbsp;`login: Admin`  
    &nbsp;&nbsp;&nbsp;&nbsp;`password: 12345`
 
