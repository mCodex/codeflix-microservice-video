import React from 'react';

import Breadcrumb from '../../../components/Breadcrumb';
import PageSkeleton from '../../../components/PageSkeleton';

interface Props {}

const Category: React.FC<Props> = () => {
  return (
    <div>
      <Breadcrumb />
      <PageSkeleton title="Listagem Categorias" />
    </div>
  );
};

export default Category;
