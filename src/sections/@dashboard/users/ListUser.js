// import React, { useState, useEffect } from 'react';
// import { DataGrid } from '@mui/x-data-grid';

// const columns = [
//   { field: 'id', headerName: 'ID' },
//   { field: 'title', headerName: 'Title', width: 300 },
//   { field: 'body', headerName: 'Body', width: 600 },
//   { id: 'action', headerName: 'Action', width: 200 },
// ];

// const ListUser = () => {
//   const [tableData, setTableData] = useState([]);

//   useEffect(() => {
//     fetch('https://jsonplaceholder.typicode.com/posts')
//       .then((data) => data.json())
//       .then((data) => setTableData(data));
//   }, []);

//   console.log(tableData);

//   return (
//     <div style={{ height: 700, width: '100%' }}>
//       <DataGrid rows={tableData} columns={columns} pageSize={12} />
//     </div>
//   );
// };

// export default ListUser;
import React, { useState, useEffect } from 'react';
import { DataGrid } from '@mui/x-data-grid';
import Avatar from '@mui/material/Avatar';
import { styled } from '@mui/material/styles';
import Badge from '@mui/material/Badge';
import { Helmet } from 'react-helmet-async';
import CheckCircleOutlineIcon from '@mui/icons-material/CheckCircleOutline';
import BlockIcon from '@mui/icons-material/Block';
import Label from '../../../components/label';

const StyledBadge = styled(Badge)(({ theme }) => ({
  '& .MuiBadge-badge': {
    backgroundColor: '#44b700',
    color: '#44b700',
    boxShadow: `0 0 0 2px ${theme.palette.background.paper}`,
    '&::after': {
      position: 'absolute',
      top: 0,
      left: 0,
      width: '100%',
      height: '100%',
      borderRadius: '50%',
      animation: 'ripple 1.2s infinite ease-in-out',
      border: '1px solid currentColor',
      content: '""',
    },
  },
  '@keyframes ripple': {
    '0%': {
      transform: 'scale(.8)',
      opacity: 1,
    },
    '100%': {
      transform: 'scale(2.4)',
      opacity: 0,
    },
  },
}));

const columns = [
  { field: 'id', headerName: 'ID', width: 25 },
  {
    field: 'avatar',
    headerName: 'Avatar',
    width: 100,
    renderCell: (params) => {
      console.log(params);
      return (
        <React.Fragment key={Math.random() * 10}>
          <StyledBadge
            overlap="circular"
            key={Math.random() * 10}
            anchorOrigin={{ vertical: 'bottom', horizontal: 'right' }}
            variant={params.row.status === '0' ? '' : 'dot'}
          >
            <Avatar
              key={Math.random() * 10}
              alt={params.row.nama}
              src={params.row.avatar ? params.row.avatar : params.row.nama}
            />
          </StyledBadge>
        </React.Fragment>
      );
    },
  },
  { field: 'nama', headerName: 'Nama Lengkap', width: 200 },

  { field: 'Email', headerName: 'Email terdaftar', width: 300 },
  {
    field: 'Role',
    headerName: 'Role Akun',
    width: 150,
    renderCell: (params) => {
      return <Label key={Math.random() * 10}>{params.row.Role === '1' ? 'Admin' : 'User'}</Label>;
    },
  },
  {
    field: 'statusAkun',
    headerName: 'Status Akun',
    width: 100,
    renderCell: (params) => {
      if (params.row.statusAkun === '1') {
        return <CheckCircleOutlineIcon key={Math.random() * 10} color="success" />;
      }
      return <BlockIcon key={Math.random() * 10} color="error" />;
    },
  },
  {
    field: 'status',
    headerName: 'Status',
    width: 200,

    renderCell: (params) => {
      return (
        <Label key={Math.random() * 10} color={params.row.status === '1' ? 'success' : 'error'}>
          {params.row.status === '1' ? 'Aktif' : 'Tidak aktif'}
        </Label>
      );
    },
  },
  { id: 'action', headerName: 'aa', width: 25 },
];

const ListUser = () => {
  const [tableData, setTableData] = useState([]);
  const [rowsPerPage, setRowsPerPage] = useState(5);
  useEffect(() => {
    fetch('http://localhost:8080/User/Joinan')
      .then((data) => data.json())
      .then((data) => setTableData(data));
  }, []);

  console.log(tableData);

  return (
    <>
      <Helmet>
        <title>Users | HarumanApps</title>
      </Helmet>
      <div style={{ height: 700, width: '100%' }}>
        <DataGrid
          key={Math.random() * 10}
          rows={tableData.map((item, index) => ({
            id: index,
            nama: item.nama_lengkap_user,
            avatar: `https://ucarecdn.com/${item.avatarURL}/`,
            Role: item.role_user,
            statusAkun: item.user_aktif,
            status: item.user_login,
            Email: item.mail_user,
          }))}
          columns={columns}
          pageSize={5}
          rowsPerPage={rowsPerPage}
          rowsPerPageOptions={[5, 10, 25]}
        />
      </div>
    </>
  );
};

export default ListUser;
