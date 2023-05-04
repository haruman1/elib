import { useState, useEffect } from 'react';
import Swal from 'sweetalert2';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import CryptoJS from 'crypto-js';
// @mui
import { alpha } from '@mui/material/styles';
import { Box, Divider, Typography, Stack, MenuItem, Avatar, IconButton, Popover } from '@mui/material';
// mocks_
import account from '../../../_mock/account';
import dataLogin from '../../../data/UserLogin';
// ----------------------------------------------------------------------

const MENU_OPTIONS = [
  {
    label: 'Home',
    icon: 'eva:home-fill',
  },
  {
    label: 'Profile',
    icon: 'eva:person-fill',
  },
  {
    label: 'Settings',
    icon: 'eva:settings-2-fill',
  },
];

// ----------------------------------------------------------------------

export default function AccountPopover() {
  const [check, setCheck] = useState(false);
  const navigate = useNavigate();
  const [open, setOpen] = useState(null);
  const [pesan, setPesan] = useState('');
  useEffect(() => {
    checkUser();
  }, []);
  const checkUser = async () => {
    if (sessionStorage.getItem('email') !== null || sessionStorage.getItem('role') !== null) {
      await axios
        .post('http://localhost:8080/api/checkUser', {
          email: sessionStorage.getItem('email'),
          role: sessionStorage.getItem('role'),
        })
        .then((response) => {
          console.log(response);
          if (response.data === 'User not found') {
            navigate('/login', { replace: true });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    } else {
      navigate('/login', { replace: true });
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Kamu belum login',
      });
    }
  };

  const handleOpen = (event) => {
    setOpen(event.currentTarget);
  };
  const logoutUser = async () => {
    await axios
      .post('http://localhost:8080/api/auth/logout', {
        email: localStorage.getItem('email'),
      })
      .then((response) => {
        sessionStorage.removeItem('email');
        sessionStorage.removeItem('role');

        setPesan(response.data.messages);
        Swal.fire({
          icon: 'success',
          title: response.data.messages,
        });
        console.log(response);
      })
      .catch((error) => {
        console.log(error);
      });
    navigate('/login', { replace: true });
  };
  const handleClose = async () => {
    setOpen(null);
  };

  return (
    <>
      <IconButton
        onClick={handleOpen}
        sx={{
          p: 0,
          ...(open && {
            '&:before': {
              zIndex: 1,
              content: "''",
              width: '100%',
              height: '100%',
              borderRadius: '50%',
              position: 'absolute',
              bgcolor: (theme) => alpha(theme.palette.grey[900], 0.8),
            },
          }),
        }}
      >
        <Avatar src={account.photoURL} alt="photoURL" />
      </IconButton>

      <Popover
        open={Boolean(open)}
        anchorEl={open}
        onClose={handleClose}
        anchorOrigin={{ vertical: 'bottom', horizontal: 'right' }}
        transformOrigin={{ vertical: 'top', horizontal: 'right' }}
        PaperProps={{
          sx: {
            p: 0,
            mt: 1.5,
            ml: 0.75,
            width: 180,
            '& .MuiMenuItem-root': {
              typography: 'body2',
              borderRadius: 0.75,
            },
          },
        }}
      >
        <Box sx={{ my: 1.5, px: 2.5 }}>
          <Typography variant="subtitle2" noWrap>
            {account.displayName}
          </Typography>
          <Typography variant="body2" sx={{ color: 'text.secondary' }} noWrap>
            {account.email}
          </Typography>
        </Box>

        <Divider sx={{ borderStyle: 'dashed' }} />

        <Stack sx={{ p: 1 }}>
          {MENU_OPTIONS.map((option) => (
            <MenuItem key={option.label} onClick={handleClose}>
              {option.label}
            </MenuItem>
          ))}
        </Stack>

        <Divider sx={{ borderStyle: 'dashed' }} />

        <MenuItem onClick={logoutUser} sx={{ m: 1 }}>
          Logout
        </MenuItem>
      </Popover>
    </>
  );
}
