import { useState } from 'react';
import axios from 'axios';

import Swal from 'sweetalert2';
import { useNavigate } from 'react-router-dom';
// @mui
import { Link, Stack, IconButton, InputAdornment, TextField, Checkbox } from '@mui/material';

// components
import Iconify from '../../../components/iconify';
import SubmitButton from '../submitButton';
// ----------------------------------------------------------------------

export default function LoginForm() {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');

  const navigate = useNavigate();
  // semua error
  const [errorData, setErrorData] = useState('');
  const [showPassword, setShowPassword] = useState(false);

  const handleClick = async () => {
    await axios
      .post('http://localhost:8080/api/auth/login', {
        login_user: username,
        login_password: password,
      })
      .then((response) => {
        sessionStorage.setItem('email', response.data.data.email);
        sessionStorage.setItem('role', response.data.data.role);
        navigate('/dashboard', { replace: true });
      })
      .catch((error) => {
        console.log(error);
        setErrorData(error.response.data.messages);
        if (
          error.response &&
          error.response.data &&
          error.response.data.messages &&
          error.response.data.messages.messages &&
          error.response.data.messages.messages.error !== null
        ) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.response.data.messages.messages.error,
          });
        }
      });
  };

  return (
    <>
      <form>
        <Stack spacing={3}>
          <TextField
            error={!!errorData.login_user}
            helperText={errorData.login_user}
            name="login_email"
            label="Email / Username Adress"
            onChange={(e) => setUsername(e.target.value)}
          />

          <TextField
            error={!!errorData.login_password}
            helperText={errorData.login_password}
            name="login_password"
            label="Password"
            onChange={(e) => setPassword(e.target.value)}
            type={showPassword ? 'text' : 'password'}
            InputProps={{
              endAdornment: (
                <InputAdornment position="end">
                  <IconButton onClick={() => setShowPassword(!showPassword)} edge="end">
                    <Iconify icon={showPassword ? 'eva:eye-fill' : 'eva:eye-off-fill'} />
                  </IconButton>
                </InputAdornment>
              ),
            }}
          />
        </Stack>

        <Stack direction="row" alignItems="center" justifyContent="space-between" sx={{ my: 2 }}>
          <Checkbox name="remember" label="Remember me" />
          <Link variant="subtitle2" underline="hover" href="/forgot">
            Forgot password?
          </Link>
        </Stack>

        <SubmitButton label="Login" onSubmit={handleClick} />
      </form>
    </>
  );
}
