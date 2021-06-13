import React from 'react';
import type { AppProps } from 'next/app';
import Head from 'next/head';

import Navbar from '../src/components/Navbar';

const RootApp = ({ Component, pageProps }: AppProps): JSX.Element => {
  return (
    <>
      <Head>
        <link
          rel="cstylesheet"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        />
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
      </Head>

      <Navbar />

      <Component {...pageProps} />
    </>
  );
};

export default RootApp;
