from pykrige.ok3d import OrdinaryKriging3D
import numpy as np
from mayavi import mlab


def kriging_interpolation(datas):
    #datas的类型

    data = np.array(datas)

    gridx = np.arange(0.0, 60, 5)
    gridy = np.arange(0.0, 60, 5)
    gridz = np.arange(0.0, 60, 5)

    # Create the 3D ordinary kriging object and solves for the three-dimension kriged
    # volume and variance. Refer to OrdinaryKriging3D.__doc__ for more information.
    #linear, power, gaussian, spherical, exponential, hole-effect
    ok3d = OrdinaryKriging3D(data[:, 0], data[:, 1], data[:, 2], data[:, 3],
                                                     variogram_model='linear',enable_plotting=True)
    k3d, ss3d = ok3d.execute('grid', gridx, gridy, gridz)
    #输出到txt
    # data=open("..\data.txt",'a')
    # for z in range(gridz.size):
    #     for y in range(gridy.size):
    #         for x in range(gridx.size):
    #             print(k3d[z][y][x],file=data,end=",")
    #         print('\n',file=data,end=" ")
    #     print('\n\n',file=data,end=" ")
    #
    # data.close()

    for z in range(gridz.size):
        pl = mlab.surf(gridx, gridy, k3d[z], warp_scale="auto")
        mlab.axes(xlabel='x', ylabel='y', zlabel='z')
        mlab.outline(pl)
        mlab.show()
    return gridx, gridy, gridz, k3d, ok3d

#绘制3D预测图，每个曲面中各点数值相等
def draw_3d_datas(x,y,z,k3d):
    mlab.volume_slice(k3d, plane_orientation='x_axes')
    mlab.axes(xlabel='x', ylabel='y', zlabel='z')
    # mlab.surf()
    mlab.contour3d(k3d)
    mlab.show()

    # pl = mlab.surf(x, y, k3d[5], warp_scale="auto")
    # mlab.axes(xlabel='x', ylabel='y', zlabel='z')
    # mlab.outline(pl)
    # mlab.show()
    
    
    for i in range(z.size):
        pl = mlab.surf(x, y, k3d[i],colormap="gist_earth", warp_scale="auto")
        mlab.axes(xlabel='x', ylabel='y', zlabel='z')
        mlab.outline(pl)
        mlab.show()

if __name__ == '__main__':
    datas = [[20, 20, 10, 1.77],
             [40, 40, 10, 1.72],
             [20, 20, 20, 1.98],
             [40, 40, 20, 1.94],
             [20, 20, 30, 2.21],
             [40, 40, 30, 2.17],
             [20, 20, 40, 2.35],
             [40, 40, 40, 2.30]]
    x,y,z,k3d,ok3d=kriging_interpolation(datas)
    draw_3d_datas(x,y,z,k3d)
