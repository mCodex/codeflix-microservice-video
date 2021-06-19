import React from 'react';

import PageSkeleton from '../../components/PageSkeleton';

interface Props {}

const Dashboard: React.FC<Props> = () => {
  return (
    <div>
      <PageSkeleton title="Dashboard" />
    </div>
  );
};

export default Dashboard;
