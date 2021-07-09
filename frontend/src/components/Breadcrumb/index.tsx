import React, { useCallback, useMemo } from 'react';
import Typography from '@material-ui/core/Typography';
import Breadcrumbs from '@material-ui/core/Breadcrumbs';
import MaterialLink from '@material-ui/core/Link';
import { Container } from '@material-ui/core';
import Link from 'next/link';
import { useRouter } from 'next/router';
import RouteParser from 'route-parser';

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

        const parsedRoute = Object.keys(breadcrumbNameMap).find((path) =>
          new RouteParser(path).match(to)
        );

        if (!parsedRoute) {
          return false;
        }

        return last ? (
          <Typography color="textPrimary" key={to}>
            {breadcrumbNameMap[parsedRoute]}
          </Typography>
        ) : (
          <Link passHref href={to} key={to}>
            <MaterialLink color="inherit" className={classes.linkRouter}>
              {breadcrumbNameMap[parsedRoute]}
            </MaterialLink>
          </Link>
        );
      }),
    []
  );

  return (
    <Container>
      <Breadcrumbs aria-label="breadcrumb">{renderBreadcrumbs()}</Breadcrumbs>
    </Container>
  );
};

export default Breadcrumb;
