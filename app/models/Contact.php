<?php

require_once __DIR__ . '/Model.php';

class Contact extends Model
{
    /** static data */
    public    static $table         = 'contacts';
    public    static $table_columns = [];
    protected static $basic_columns = ['id', 'name', 'email', 'message'];

    /** properties */
    protected $name    = '';
    protected $email   = '';
    protected $message = '';

    /**
     * Constructor
     * @param $id
     */
    public function __construct($id = 0)
    {
        parent::__construct();

        if ($id > 0) {
            $stmt = $this->getConnection()->prepare("SELECT * FROM `" . self::$table . "` WHERE `id` = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $this->hydrate($row);
            }
        }
    }


    /**
     * Gets Contact name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Gets Contact email.
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Gets Contact message.
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }


    /**
     * Sets Contact name.
     * @param $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * Sets Contact email.
     * @param $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


    /**
     * Sets Contact message.
     * @param $message
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


    /**
     * Get all Contact.
     * @param $assoc
     * @param $assoc_basic
     * @return array
     */
    public static function all($assoc = false, $assoc_basic = false)
    {
        $contacts = [];

        $stmt = self::getConnectionStatic()->prepare("SELECT * FROM `" . self::$table . "`");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $contact = new Contact();
                $contact->hydrate($row);

                $contacts[] = $assoc ? $contact->getAssoc($assoc_basic) : $contact;
            }
        }

        return $contacts;
    }


    /**
     * Insert contact
     *
     * @return bool
     * @throws Exception
     */
    public function insert(): bool
    {
        $stmt = $this->getConnection()->prepare("INSERT INTO `" . self::$table . "` (`name`, `email`, `message`) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $this->name, $this->email, $this->message);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $this->setId($stmt->insert_id);
            return true;
        }
        return false;
    }


    /**
     * Update contact
     *
     * @return bool
     * @throws Exception
     */
    public function update(): bool
    {
        $stmt = $this->getConnection()->prepare("UPDATE `" . self::$table . "` SET `name` = ?, `email` = ?, `message` = ? WHERE `id` = ?");
        $stmt->bind_param("sssi", $this->name, $this->email, $this->message, $this->id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }


    /**
     * Delete sk_official
     *
     * @return bool
     * @throws Exception
     */
    public function delete(): bool
    {
        $stmt = $this->getConnection()->prepare("DELETE FROM `" . self::$table . "` WHERE `id` = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }


    /**
     * Test email sending
     * @throws Exception
     */
    public function sendHelloEmail(): bool
    {
        require_once __DIR__ . '/../helpers/Mailer.php';

        $mailer = new Mailer();
        $mailer->setSubject('Hello World!');
        $mailer->setBody("
            <h1>Hello " . $this->getName() . "!</h1>
            <p>This is a test email.</p>
        ");
        $mailer->addRecipient($this->getEmail());

        return $mailer->send();
    }
}
