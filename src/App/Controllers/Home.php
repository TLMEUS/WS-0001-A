<?php
/**
 * This file contains the App/Controllers/Home.php class for project WS-0001-A
 *
 * File Information:
 * Project Name: WS-0001-A
 * Module Name: App/Controllers
 * File Name: Home.php
 * File Version: 1.0.0
 * Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Copyright: 01/2024
 */
namespace App\Controllers;

use App\Database;
use App\Models\ApplicationModel;
use App\Models\UserAccessModel;
use Framework\Controller;
use Framework\Response;
use App\Models\UserModel;
use App\Models\LoginModel;


/**
 * Class Home
 *
 * This class represents the Home controller.
 *
 * @noinspection PhpUnused
 */
class Home extends Controller {

    protected UserModel $userModel;

    protected ApplicationModel $applicationModel;

    protected UserAccessModel $userAccessModel;
    protected LoginModel $loginModel;

    /**
     * Constructs a new object of the class.
     *
     * Initializes a new instance of the class by creating a new Database object which is used for database connections.
     * It also initializes the $homeModel and $applicationModel properties using the newly created Database object.
     *
     * @return void
     *
     * @noinspection PhpUnused
     */
    public function __construct() {
        $database = new Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $this->userModel = new UserModel($database);
        $this->applicationModel = new ApplicationModel($database);
        $this->userAccessModel = new UserAccessModel($database);
        $this->loginModel = new LoginModel($database);
    }

    /**
     * Renders the index page of the Home controller.
     *
     * This method renders the index page of the Home controller by returning a Response object. The index page
     * template is specified as "Home/index.tlmt" and the data passed to the template
     * includes the given `$app` variable.
     *
     * @param string $app The value of the `$app` variable to pass to the index page template.
     *
     * @return Response The Response object representing the index page view.
     *
     * @noinspection PhpUnused
     */
    public function index(string $app): Response {
        return $this->view(template: "Home/index.tlmt", data: ['app' => $app]);
    }

    /**
     * Display the "no access" page.
     *
     * @return Response Returns a Response object representing the "no access" page.
     *
     * @noinspection PhpUnused
     */
    public function noaccess(): Response {
        return $this->view(template: "Home/noaccess.tlmt");
    }

    /**
     * Logs in a user.
     *
     * This method logs in a user based on the provided user data. It first loads the user data using the
     * `loadUserData()` method. Then it verifies the user using the `verifyUser()` method. If the user is verified,
     * it checks if the password is set to "NewHire". If so, it redirects the user to the password set page. Otherwise,
     * it loads the application data using the `loadAppData()` method. If the user has access to the application,
     * it redirects the user to the application's domain. If the user does not have access, it redirects the user to
     * the home page.
     *
     * @return Response The response object that represents the redirection.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     *
     * @noinspection PhpUnused
     */
    public function login():Response {
        $userData = $_POST;
        $userArray = $this->loadUserData($userData['username']);
        if ($this->verifyUser($userData, $userArray)) {
            if ($userData["password"] == "NewHire") {
                return $this->redirect(url: '/setpass/' . $userArray["colId"] . '/' .
                    $userData['username'] . '/' . $userData['app'] . '/' . $userArray['colDepartment'] . '/' .
                    $userArray['colRole']);
            }
        } else {
            return $this->view(template: "Home/denyaccess.tlmt");
        }
        return $this->redirectToApp(userData: $userData, userArray: $userArray);
    }

    /**
     * Sets the password for a user.
     *
     * This method sets the password for a user by rendering the "setpass.tlmt" template with the provided parameters.
     * The parameters include the user ID, application, department, and role.
     * The rendered template is returned as a Response object.
     *
     * @param string $id The ID of the user. Default value is "0".
     * @param string $app The application. Default value is "0".
     * @param string $dep The department. Default value is "0".
     * @param string $rol The role. Default value is "0".
     *
     * @return Response The rendered template as a Response object.
     *
     * @noinspection PhpUnused
     */
    public function setpass(string $id = "0", string $username = "", string $app = "0", string $dep = "0", string $rol = "0"): Response {
        return $this->view(template: "Home/setpass.tlmt", data: [  'id' => $id,
                                                                    'username' => $username,
                                                                    'app'=> $app,
                                                                    'dep' => $dep,
                                                                    'rol' => $rol]);
    }

    /**
     * Sets the password for the user.
     *
     * Retrieves the password data from the POST request and checks if the entered passwords match.
     * If the passwords don't match, it returns a Response containing a view template "Home/nmpass.tlmt",
     * along with additional data including the user's ID, application, department, and role.
     *
     * If the passwords match, it calls the changePassword method of the $homeModel object, passing the user ID and the
     * new password.
     *
     * After successfully changing the password, it returns a Response with a redirection URL to the "/Home/login"
     * route.
     *
     * @return Response The Response object containing either a view template or a redirection URL.
     *
     * @noinspection PhpUnused
     */
    public function setPassword(): Response {
        $userData = $_POST;
        $userArray = $this->loadUserData($userData['username']);
        if ($userData['password1'] !== $userData['password2']) {
            return $this->view(template: "Home/nmpass.tlmt", data: [  'id' => $userData['id'],
                                                                        'username' => $userData['username'],
                                                                        'app' => $userData['app'],
                                                                        'dep' => $userData['dep'],
                                                                        'rol' => $userData['rol']]);
        } else {
            $this->userModel->changePassword($userData['id'], $userData['password1']);
            return $this->redirectToApp(userData: $userData, userArray: $userArray);
        }
    }

    /**
     * Redirect the user to the specified application.
     *
     * @param array $userData The user data, including the name of the application to redirect to.
     * @param array $userArray The user information array.
     *
     * @return Response Returns the Response object for the redirect if the user has access to the application,
     *                 otherwise returns the Response object for the login page.
     */
    public function redirectToApp(array $userData, array $userArray):Response {
        $appData = $this->loadAppData($userData['app']);
        $access = $this->userAccessModel->getUserAccess(user: $userArray['colId'], app: $appData['colId']);
        $loggedUser = $this->loginModel->loginUser(
            $userArray['colId'],
            $userArray['colDepartment'],
            $userArray['colRole'],
            $access
        );
        setcookie("LoggedUser",$loggedUser, time() + (60 * 5), "/", "tlme.us");
        if ($this->roleAccess($userArray["colId"], $userData["app"])) {
            return $this->redirect(url: "https://" . $appData['colDomain'] . '.' . $_ENV['DOMAIN'] . "/" . $loggedUser);
        } else if ($this->userAccess($userArray["colId"], $userData["app"])) {
            return $this->redirect(url: "https://" . $appData['colDomain'] . '.' . $_ENV['DOMAIN'] . "/" . $loggedUser);
        }
        return $this->login();
    }

    /**
     * Loads user data by username.
     *
     * Retrieves the user data by searching the HomeModel for a matching username key ("colUsername") and value.
     *
     * @param string $username The username to search for.
     * @return array|bool Returns an array containing the user data if found, or false if no matching data is found.
     *
     * @noinspection PhpUnused
     */
    private function loadUserData(string $username): array|bool {
        return $this->userModel->findByKey("colName", $username);
    }

    /**
     * Verifies if the password provided by the user matches the hashed password stored in the user array.
     *
     * Compares the password provided by the user with the hashed password stored in the user array.
     *
     * @param array $userData An array containing user data, including the password provided by the user.
     * @param array $userArray An array containing user data, including the hashed password stored in the database.
     * @return bool Returns true if the password matches, false otherwise.
     *
     * @noinspection PhpUnused
     */
    private function verifyUser(array $userData, array $userArray): bool {
        return password_verify($userData["password"], $userArray["colPassword"]);
    }

    /**
     * Load the application data for the given target.
     *
     * @param string $target The target to load the data for.
     *
     * @return array|bool Returns an array representing the application data if found,
     *                   otherwise returns false if data not found.
     */
    private function loadAppData(string $target): array|bool {
        return $this->applicationModel->find(id: $target);
    }

    /**
     * Determine if the user's role has access to the specified application.
     *
     * @param string $user The user identifier.
     * @param string $app The application identifier.
     *
     * @return bool Returns true if the user role has access to the application,
     *              otherwise returns false.
     */
    private function roleAccess(string $user, string $app): bool {
        $userData = $this->userModel->find($user);
        $appData = $this->applicationModel->find($app);
        if($appData['colDepartment'] <= $userData['colDepartment'] && $appData['colRole'] <= $userData['colRole']) {
            return true;
        }
        return false;
    }

    /**
     * Validate user access for the given user and application.
     *
     * @param string $user The username of the user.
     * @param string $app The application to validate the user access for.
     *
     * @return bool Returns true if the user has access to the application,
     *              otherwise returns false.
     */
    private function userAccess(string $user, string $app): bool {
        return $this->userAccessModel->validateUser(user: $user, app: $app);
    }
}