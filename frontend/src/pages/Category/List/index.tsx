import React from 'react';

import PageSkeleton from '../../../components/PageSkeleton';

interface Props {}

const Category: React.FC<Props> = () => {
  return (
    <div>
      <PageSkeleton title="Listagem Categorias" />
    </div>
  );
};

export default Category;
