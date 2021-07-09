import { makeStyles, createStyles } from '@material-ui/core/styles';

const useStyles = makeStyles(() =>
  createStyles({
    linkRouter: {
      color: '#4db5ab',
      '&:focus, &:active': {
        color: '#4db5ab',
      },
      '&:hover': {
        color: '#055a52',
      },
    },
  })
);

export default useStyles;
