import { faker } from '@faker-js/faker';
import { sample } from 'lodash';

// ----------------------------------------------------------------------
const getUsers = async () => {
  const response = await fetch('http://localhost:8080/User/Joinan');
  const data = await response.json();
  return data.map((user) => ({
    id: user.user_id,
    avatarUrl: `https://ucarecdn.com/${user.avatarUrl}/-/resize/100x/`,
    name: user.nama_lengkap_user,
    aktif: user.user_aktif,
    role: user.role_user,
    login: user.user_login,
  }));
};
const users = [...Array(24)].map((_, index) => ({
  id: faker.datatype.uuid(),
  avatarUrl: `/assets/images/avatars/avatar_${index + 1}.jpg`,
  name: faker.name.fullName(),
  company: faker.company.name(),
  isVerified: faker.datatype.boolean(),
  status: sample(['active', 'banned']),
  role: sample([
    'Leader',
    'Hr Manager',
    'UI Designer',
    'UX Designer',
    'UI/UX Designer',
    'Project Manager',
    'Backend Developer',
    'Full Stack Designer',
    'Front End Developer',
    'Full Stack Developer',
  ]),
}));

export default users;
