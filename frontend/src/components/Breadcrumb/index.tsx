import React, { useCallback, useMemo } from 'react';
import Typography from '@material-ui/core/Typography';
import Breadcrumbs from '@material-ui/core/Breadcrumbs';
import MaterialLink from '@material-ui/core/Link';
import Link from 'next/link';
import { useRouter } from 'next/router';

import routes from '../../routes';

import useStyles from './useStyles';

const Breadcrumb: React.FC = () => {
  const route = useRouter();

  const classes = useStyles();

  const breadcrumbNameMap: { [key: string]: string } = useMemo(() => {
    let obj = {};

    routes.forEach(({ path, label }) => {
      obj = {
        ...obj,
        [path]: label,
      };
    });

    return obj;
  }, []);

  const pathnames = useMemo(() => {
    const allPathnames = route.pathname.split('/').filter((x) => x);

    allPathnames.unshift('/');

    return allPathnames;
  }, [route.pathname]);

  const renderBreadcrumbs = useCallback(
    () =>
      pathnames.map((_, index) => {
        const last = index === pathnames.length - 1;
        const to = `${pathnames
          .slice(0, index + 1)
          .join('/')
          .replace('//', '/')}`;

        return last ? (
          <Typography color="textPrimary" key={to}>
            {breadcrumbNameMap[to]}
          </Typography>
        ) : (
          <Link passHref href={to} key={to}>
            <MaterialLink color="inherit">{breadcrumbNameMap[to]}</MaterialLink>
          </Link>
        );
      }),
    []
  );

  return (
    <div className={classes.root}>
      <Breadcrumbs aria-label="breadcrumb">{renderBreadcrumbs()}</Breadcrumbs>
    </div>
  );
};

export default Breadcrumb;
