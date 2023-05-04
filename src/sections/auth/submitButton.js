import React, { useState } from 'react';
import { LoadingButton } from '@mui/lab';

import spinner from './Spinner-1s-200px.gif';

function SubmitButton(props) {
  const [loading, setLoading] = useState(false);

  const handleClick = async () => {
    try {
      setLoading(true);
      await props.onSubmit();
    } finally {
      setLoading(false);
    }
  };

  return (
    <LoadingButton fullWidth size="large" type="submit" variant="contained" disabled={loading} onClick={handleClick}>
      {loading ? (
        <img src={spinner} alt="Loading..." style={{ width: '50px' }} defaultValue={'Loading ...'} />
      ) : (
        props.label
      )}
    </LoadingButton>
  );
}

export default SubmitButton;
