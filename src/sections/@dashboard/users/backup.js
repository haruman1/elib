import * as React from 'react';
import Box from '@mui/material/Box';
import { DataGrid } from '@mui/x-data-grid';
import { useState, useEffect } from 'react';
import axios from 'axios';
import { styled } from '@mui/material/styles';
import Badge from '@mui/material/Badge';
import Avatar from '@mui/material/Avatar';
import Stack from '@mui/material/Stack';

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

const SmallAvatar = styled(Avatar)(({ theme }) => ({
  width: 22,
  height: 22,
  border: `2px solid ${theme.palette.background.paper}`,
}));

function useGenerateState() {
  const [avarData, setAvarData] = useState([]);
  useEffect(() => {
    getAvatar();
  }, []);
  const getAvatar = async () => {
    const response = await axios.get('http://localhost:8080/User/Joinan');
    console.log(response.data.response);
    setAvarData(response.data.response);
    return columns;
  };
  const columns = [
    { field: 'Number', headerName: 'No', width: 90 },
    {
      field: 'UserID',
      headerName: 'ID User',
      width: 150,
      editable: true,
    },
    {
      field: 'Nama',
      headerName: 'Nama Lengkap',
      width: 150,
      editable: true,
    },

    {
      field: 'Avatar',
      headerName: 'Avatar',
      Image: 'Avatar',
      width: 150,
      renderCell: (params) => {
        return (
          <>
            <StyledBadge
              overlap="circular"
              anchorOrigin={{ vertical: 'bottom', horizontal: 'right' }}
              variant={avarData.user_login ? 'dot' : ''}
            >
              <Avatar alt="Remy Sharp" src="/static/images/avatar/1.jpg" />
            </StyledBadge>
          </>
        );
      },
    },

    {
      field: 'Email',
      headerName: 'Email Lengkap',
      type: 'email',
      width: 170,
      editable: true,
    },
    {
      field: 'HakAkses',
      headerName: 'Hak Akses',
      type: 'number',
      width: 100,
      editable: false,
    },
    {
      field: 'JenisKelamin',
      headerName: 'Jenis Kelamin',
      description: 'This column has a value getter and is not sortable.',
      sortable: false,
      width: 160,
    },
    {
      field: 'Aktif',
      headerName: 'Sedang apa?',
      width: 100,

      editable: true,
    },
  ];
}
export default function ListUser() {
  const [User, setUser] = useState([]);
  const [Avatar, setAvatar] = useState([]);
  useEffect(() => {
    getAvatar();
  }, []);

  const getAvatar = async () => {
    const response = await axios.get('http://localhost:8080/User/Joinan');
    console.log(response.data.response);
    setAvatar(response.data.response);
  };
  const index = 0;
  const rows = Avatar.map((item, index) => ({
    id: index + 1,
    UserID: item.user_id,
    Number: index + 1,
    Nama: item.nama_lengkap_user,
    Email: item.mail_user,
    JenisKelamin: item.jenisKelamin,
    HakAkses: item.role_user,
    Aktif: item.user_login,
  }));
  console.log(Avatar);

  return (
    <Box sx={{ height: 400, width: '100%' }}>
      <DataGrid
        rows={rows}
        columns={<useGenerateState />}
        initialState={{
          pagination: {
            paginationModel: {
              pageSize: 5,
            },
          },
        }}
        pageSizeOptions={[5]}
        checkboxSelection
        disableRowSelectionOnClick
      />
    </Box>
  );
}
