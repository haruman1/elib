<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use App\Libraries\Bcrypt;

use Daycry\Encryption\Encryption;

class User extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->bcrypt = Bcrypt::class;
        $db = \Config\Database::connect();
        $this->userModel = new UserModel();
        $this->request = \Config\Services::request();

        $this->builder = $db->table('daftaruser');
        $validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
    }
    public function index($id = null)
    {
        $limit = $this->request->getGet('limit');
        if ($limit) {
            $dataUser = $this->userModel->findAll($limit);
        } else {
            $dataUser = $this->userModel->findAll();
        }
        return $this->respond(
            [
                'status' =>  200,
                'code' => 'OK',
                'message' => 'Data berhasil diambil',
                'response' => $dataUser
            ]
        );
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $dataUser = $this->userModel->find(['id_image' => $id]);
        if (!$dataUser) {
            return $this->failNotFound('Data tidak ditemukan');
        } else {
            return $this->respond([
                'status' =>  200,
                'code' => 'OK',
                'message' => 'Data berhasil diambil',
                'response' => $dataUser
            ]);
        }
    }
    public function checkCode()
    {
        $rules = [
            'email' => [
                'rules' => 'required|trim',
                'errors' => [
                    'required' => 'Email wajib ada',
                ]
            ],
            'role' => [
                'rules' => 'required|trim',
                'errors' => [
                    'required' => 'Role wajib ada',
                ]
            ],

        ];
        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $salt = file_get_contents('https://raw.githubusercontent.com/haruman1/himee/master/salt.txt');
        $decrypt = new Encryption();
        $email = $decrypt->setCipher('AES-256-CBC')->setKey($salt)->decrypt($this->request->getVar('email'));
        $role = $decrypt->setCipher('AES-256-CBC')->setKey($salt)->decrypt($this->request->getVar('role'));
        if ($email == null || $role == null) {
            return $this->failNotFound('Data tidak ditemukan');
        }
        $this->builder->select('userimage.user_id, daftaruser.nama_lengkap_user,userimage.avatarURL, daftaruser.mail_user, daftaruser.jenisKelamin,daftaruser.user_aktif, daftaruser.role_user, daftaruser.user_login  ');
        $this->builder->join('userimage', 'daftaruser.id_user = userimage.user_id');
        $this->builder->where('daftaruser.mail_user', $email);
        $this->builder->where('daftaruser.role_user', $role);
        $query = $this->builder->get();
        if ($query->getResultObject() == null) {
            return $this->failNotFound('Data tidak ditemukan');
        }
        $dataUser = $query->getRow();
        return $this->respond(
            [
                'status' =>  200,
                'code' => 'OK',
                'message' => 'Data berhasil diambil',
                'response' => [
                    'nama' => $dataUser->nama_lengkap_user,
                    'email' => $dataUser->mail_user,
                    'jenisKelamin' => $dataUser->jenisKelamin,
                    'avatar' => $dataUser->avatarURL,
                    'login' => $dataUser->user_login,
                ],
            ]
        );
    }
    public function kebutuhanUser()
    {
        $email = $this->request->getVar('email');

        $this->builder->select('userimage.user_id, daftaruser.nama_lengkap_user,userimage.avatarURL, daftaruser.mail_user, daftaruser.jenisKelamin,daftaruser.user_aktif, daftaruser.role_user, daftaruser.user_login ');
        $this->builder->join('userimage', 'daftaruser.id_user = userimage.user_id');
        $query = $this->builder->get();
        if ($query->getResultArray() == null) {
            return $this->failNotFound('Data tidak ditemukan');
        }
        $dataUser = $query->getResultArray();
        return $this->respond($dataUser, 200);
    }
    public function logout()
    {
        $authModel = new AuthModel();
        $authModel->update(['email' => $this->request->getVar('email')], ['user_login' => 0]);
        return $this->respond([
            'status' => 200,
            'messages' => 'Logout berhasil',
        ]);
    }
    // buat login ke database via api
    public function login()
    {
        $salt = file_get_contents('https://raw.githubusercontent.com/haruman1/himee/master/salt.txt');

        helper(['form']);
        $authModel = new AuthModel();
        $rules = [
            'login_user' => [
                'rules' => 'required|trim',
                'errors' => [
                    'required' => 'Email / Username wajib di isi',
                ],
            ],

            'login_password' => [
                'rules' => 'required|trim',
                'errors' => [
                    'required' => 'Password wajib di isi',
                ],
            ],
        ];
        $dataLogin = [
            'login_user' => $this->request->getVar('login_user'),
            'login_password' => $this->request->getVar('login_password'),
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $user = $authModel->getUserByUsername($dataLogin['login_user']) ?? $authModel->getUserByEmail($dataLogin['login_user']);
        if ($user) {
            if (password_verify($dataLogin['login_password'], $user['password_user'])) {
                if ($user['user_aktif'] == 0) {
                    return $this->fail([
                        'status' => 401,
                        'messages' => [
                            'error' => 'Akun anda belum aktif, silahkan Hubungin admin untuk meminta bantuan'
                        ],
                    ]);
                }
                $encrypt = new Encryption();
                $hasil = $encrypt->setCipher('AES-256-CBC')->setKey($salt)->encrypt($user['mail_user']);
                $response = [
                    'status' => 200,
                    'messages' => 'Login berhasil',
                    'data' => [
                        'email' => $encrypt->setCipher('AES-256-CBC')->setKey($salt)->encrypt($user['mail_user']),
                        'role' => $encrypt->setCipher('AES-256-CBC')->setKey($salt)->encrypt($user['role_user']),
                    ]
                ];
                $authModel->update(['id_user' => $user['id_user']], ['user_login' => 1]);
                return $this->respondCreated($response, 200);
            } else {
                return $this->fail([
                    'status' => 401,
                    'messages' => [
                        'error' => 'Password anda salah,silahkan login ke akun anda dengan password benar'
                    ],

                ]);
            }
        } else {
            return $this->fail([
                'status' => 401,
                'messages' => [
                    'error' => 'Maaf Akun tidak ditemukan, silahkan login kembali'
                ],

            ]);
        }
    }
    public function userCheck()
    {
        $rules = [
            'login_user' => [
                'rules' => 'required|trim',
                'errors' => [
                    'required' => 'Email wajib ada',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $email = $this->request->getVar('login_user');
        $this->builder->where('daftaruser.mail_user', $email);
        $this->builder->select('userimage.user_id, daftaruser.nama_lengkap_user, userimage.avatarURL, daftaruser.mail_user, daftaruser.jenisKelamin,daftaruser.user_aktif');
        $this->builder->join('userimage', 'daftaruser.id_user = userimage.user_id');
        $query = $this->builder->get();
        $row = $query->getRowArray();
        if ($row) {
            return $this->respond([
                'status' => 200,
                'data' => [
                    'nama' => $row['nama_lengkap_user'],
                    'avatar' => $row['avatarURL'],
                    'email' => $row['mail_user'],
                    'jenisKelamin' => $row['jenisKelamin'],
                ]
            ]);
        } else {
            return $this->respond([
                'status' => 200,
                'messages' => 'Email belum terdaftar',
                'data' => [
                    'email' => $this->request->getVar('email'),
                    'role' => 'user',
                ]
            ]);
        }


        //     if (!password_verify($dataLogin['login_user'], $user['password_user'])) {
        //         return $this->fail('Maaf Password salah, silahkan login kembali', 401);
        //         $authModel->update($user['id_user'], ['user_login' => 1]);
        //     }
        //     if (!$user) {
        //         return $this->fail('Maaf Username / Email salah, silahkan login kembali', 401);
        //     }
        //     $salt = file_get_contents('https://raw.githubusercontent.com/haruman1/himee/master/salt.txt');
        //     $dataUsers = [
        //         // nama lengkap
        //         'nama' => base64_encode($salt . '+' . Bcrypt::hash($user['nama_lengkap_user'])),
        //         'role' => base64_encode($salt . '+' . Bcrypt::hash($user['role_user'])),
        //         'pemanis' => base64_decode(Bcrypt::verify($user['nama_lengkap_user'], base64_encode($salt . '+' . Bcrypt::hash($user['nama_lengkap_user'])))),
        //     ];
        //     return $this->respond([
        //         'status'     => 200,
        //         'message'    => 'Login successful',
        //         'tuan'       =>   $dataUsers,
        //     ]);
        // } else {
        //     return $this->fail('Maaf Username / Email salah, silahkan login kembali', 401);
        // }
    }
    public function loginNew()
    {
        $authModel = new AuthModel();
        $session = $this->session;
        $username = $this->request->getVar('login_user');

        $password = $this->request->getVar('login_password');
        // Ambil file salt dari link
        $salt = file_get_contents('https://raw.githubusercontent.com/haruman1/himee/master/salt.txt');

        // Ambil jumlah percobaan login dari session
        $login_attempts = $session->get('login_attempts');
        if ($login_attempts === null) {
            $login_attempts = 0;
        }

        // Verifikasi password
        $user = $authModel->getUserByUsername($username) ?? $authModel->getUserByEmail($username);

        if ($user === null) {
            $login_attempts++;
            $session->set('login_attempts', $login_attempts);
            if ($login_attempts >= 3) {
                $session->set('login_timeout', time() + 10); // Tambahkan jeda selama 5 menit

                return $this->fail('Maaf username/ email anda salah sehingga anda tidak bisa login lagi, silahkan login kembali menggunakan username dengan benar', 401);
            } else {
                return $this->fail('Maaf Username / Email salah, silahkan login kembali', 401);
            }
        } else {
            $hashed_password = $user['password_user'];
            if (password_verify($password, $hashed_password)) {
                $session->remove('login_attempts');
                $session->remove('login_timeout');
                $data = [
                    'id' => $user['id_user'],
                    'username' => $user['username_user'],
                ];
                return $this->respond([
                    'status'     => 200,
                    'message'    => 'Login successful',
                    'tuan'       =>   $data,
                ]);
            } else {
                $login_attempts++;
                $session->set('login_attempts', $login_attempts);
                if ($login_attempts >= 3) {
                    $session->set('login_timeout', time() + 10); // Tambahkan jeda selama 5 menit
                    return $this->fail('Maaf anda sudah mencoba login lebih dari 3x, silahkan login secara berkala', 401);
                } else {
                    return $this->fail('Maaf Password salah, silahkan login kembali', 401);
                }
            }
        }
    }
    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
