import usersData from './Datanya';

// usersData.map((user, index) => ({
//   id: index + 1,
//   UserID: user.id_user,
//   avatarURL: user.avatar_user,
//   Nama: user.username_user,
//   Email: user.mail_user,
//   JenisKelamin: user.jenisKelamin,
//   isVerified: user.isVerified,
//   role: user.role,
//   status: user.status,
// }));
const Users = [...Array(24)].map((_, index) => ({
  id: index + 1,
  UserID: '',
  avatarURL: '',
  Nama: '',
  Email: '',
  JenisKelamin: '',
  isVerified: false,
  role: '',
  status: '',
}));

export default Users;
