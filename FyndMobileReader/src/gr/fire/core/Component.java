/*
 * Fire (Flexible Interface Rendering Engine) is a set of graphics widgets for creating GUIs for j2me applications. 
 * Copyright (C) 2006-2008 Bluevibe (www.bluevibe.net)
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */

/*
 * Created on Feb 22, 2008
 */
package gr.fire.core;

import javax.microedition.lcdui.Command;
import javax.microedition.lcdui.Font;
import javax.microedition.lcdui.Graphics;

/**
 * UI组件
 * @author padeler
 *
 */
public class Component {

    /**
     * 父组件
     */
    protected Component parent;
    /**
     * 组件ID
     */
    private String id;
    /**
     * 该组件的约束，被LayoutManager使用
     * Constrains for this object, used by the layoutManager
     */
    Object constrains = null;
    /**
     * 组件在父容器中的x坐标，以组件的左上角为基准，相对于父容器(0,0)坐标向左偏移量
     * Location of the component inside its parent container.
     * These coordinates are refer to the top left corner of this component, relative to the
     * top left corder (0,0) of the parent container.
     */
    int x;
    /**
     * 组件在父容器中的y坐标，以组件的左上角为基准，相对于父容器(0,0)坐标向下偏移量
     *
     */
    int y;
    /**
     * 组件实际宽度
     * The actual dimensions of this component
     */
    protected int width;
    /**
     * 组件实际高度
     */
    protected int height;
    /**
     * 组件显示文本时所使用的字体对象
     * The font used by this component when displaying text.
     */
    protected Font font;
    /**
     * 组件中所有物件的布局形式，如(FireScreen.TOP|FireScreen.LEFT)
     * The layout of this Component's contents i.e (FireScreen.TOP|FireScreen.LEFT)
     */
    protected int layout;
    /**
     * 组件的前景色
     */
    protected int foregroundColor;
    /**
     * 组件的背景色
     */
    protected int backgroundColor;
    /**
     * Padding is the distance of this component's border from its content
     * The Component is responsible for rendering correctly the padding.
    protected int paddingLeft;
    protected int paddingTop;
    protected int paddingRight;
    protected int paddingBottom;
    /**
     * The internal padding between the component's elements
    protected int paddingVertical;
    protected int paddingHorizontal;

    /**
     * The margin is the distance of the component from its neighbors.
     * The parent container is responsible for rendering the margins correctly.
    protected int marginLeft;
    protected int marginTop;
    protected int marginRight;
    protected int marginBottom;
     */
    /**
     * 组件是否会显示边框
     */
    protected boolean border = false;
    /**
     * 组件是否已经通过验证，如果否，则所有特征（如高、宽等）需要被验证
     * If the Component is not valid, its characteristics (with,height,etc) need validation
     */
    protected boolean valid;
    /**
     * 能否获取焦点，或能被遍历
     * If it can be focused (traversed) by the user
     */
    private boolean focusable;
    /**
     * 是否可见
     */
    boolean visible = true;
    /**
     * 如果组件运行在动画模式下，则这个字段保存对应的动画对象
     * If this component is in animation mode then the animation is held in this field.
     */
    Animation animation = null;
    /**
     * 组件当前是否被选中，比如光标在组件上
     * If the component is currently selected, i.e. the curson is on it
     */
    boolean selected;
    /**
     * Dimensions of the component that control its layout inside a Container.
     */
    private int prefWidth = -1,  prefHeight = -1;
    protected CommandListener commandListener;
    protected KeyListener keyListener;
    protected PointerListener pointerListener;
    protected Command command;
    protected Command leftSoftKeyCommand;
    protected Command rightSoftKeyCommand;
    /**
     * 初始化<code>Component<code>实例，并应用主题设置
     */
    public Component() {
        Theme t = FireScreen.getTheme();
        backgroundColor = t.getIntProperty("bg.color");
        foregroundColor = t.getIntProperty("fg.color");
    }

    /**
     * 组件的容器调用paint方法将组件绘制到Graphics上，参数g对应的可绘制区域是(0,0,width,height)
     * Paint is called by the container of the component to allow it to draw itself on Graphics g
     * The drawable area on g is (0,0,width,height).
     *
     * @param g the area on witch the component will draw it self.
     */
    public void paint(Graphics g) {
    }

    /**
     * 设置组件是否打开选择模式
     * Sets this component on/off selected mode.
     * @param v
     */
    public void setSelected(boolean v) {
        selected = v;
    }

    /**
     * 获取组件是否能被选中
     * @return
     */
    public boolean isSelected() {
        return selected;
    }

    /**
     * 验证组件，重新计算内部属性值，例如高、宽等。（未完成）
     * A validate event requests from the component to recalculate its internal properties such as width/height etc.
     */
    public void validate() {
        valid = true;
    }

    /**
     * 获取组件是否已经验证过
     * @return
     */
    public boolean isValid() {
        return valid;
    }

    /**
     * 获取组件实际高度
     * @return
     */
    public int getHeight() {
        return height;
    }
    /**
     * 设置组件实际高度,不能为负数
     * @param height
     * @exception IllegalArgumentException
     */
    public void setHeight(int height) {
        if (height < 0) {
            throw new IllegalArgumentException("Height cannot be negative.");
        }
        this.height = height;
    }

    /**
     * 获取组件实际宽度
     * @return
     */
    public int getWidth() {
        return width;
    }
    /**
     * 设置组件实际宽度
     * @param width
     * @exception IllegalArgumentException
     */
    public void setWidth(int width) {
        if (width < 0) {
            throw new IllegalArgumentException("Width cannot be negative.");
        }
        this.width = width;
    }

    /**
     * 设置组件对应的命令
     * set a command to this component.
     * @param c
     */
    public void setCommand(Command c) {
        command = c;
        focusable = (focusable || commandListener != null || keyListener != null || pointerListener != null || command != null);
    }
    /**
     * 设置命令监听器
     * @param listener
     */
    public void setCommandListener(CommandListener listener) {

        this.commandListener = listener;
        focusable = (focusable || commandListener != null || keyListener != null || pointerListener != null || command != null);
    }
    /**
     * 设置组件键盘事件监听器
     * @param listener
     */
    public void setKeyListener(KeyListener listener) {
        this.keyListener = listener;
        focusable = (focusable || commandListener != null || keyListener != null || pointerListener != null || command != null);
    }
    /**
     * 设置屏幕指针时间监听器
     * @param listener
     */
    public void setPointerListener(PointerListener listener) {
        this.pointerListener = listener;
        focusable = (focusable || commandListener != null || keyListener != null || pointerListener != null || command != null);
    }

    /**
     * 获取组件是否能获取焦点
     * @return
     */
    public boolean isFocusable() {
        return focusable;
    }

    /**
     * 检查坐标是否在组件内部。这个点必须在组件的坐标系统内，也就是说组件的左上角的坐标是(0,0)。
     * Checks if the point (x,y) is inside this Component. The point must be on the coordinate system of this Component.
     * That means that the top left corner of the component is (0,0).
     * @param x
     * @param y
     * @return
     */
    public boolean contains(int x, int y) {
        return (x >= 0 && y >= 0 && x < width && y < height);
    }

    /**
     * 判断组件是否和指定的矩形区域相交，如果两个矩形的交集不为空，则判定它们相交
     * Determines whether or not this <code>Component</code> and the specified
     * rectangular area intersect. Two rectangles intersect if their
     * intersection is nonempty.
     *
     * @return <code>true</code> 如果指定区域与组件是相交的。if the specified area and this
     *         <code>Component</code> intersect; <code>false</code>
     *         otherwise.
     */
    public boolean intersects(int rx, int ry, int rw, int rh) {
        int tw = width;
        int th = height;
        if (rw <= 0 || rh <= 0 || tw <= 0 || th <= 0) {
            return false;
        }
        int tx = x;
        int ty = y;
        rw += rx;
        rh += ry;
        tw += tx;
        th += ty;
        // overflow || intersect
        return ((rw < rx || rw > tx) && (rh < ry || rh > ty) && (tw < tx || tw > rx) && (th < ty || th > ry));
    }

    /**
     * 重新绘制组件
     */
    public void repaint() {
        repaint(0, 0, width, height);
    }

    /**
     * 获取组件在其父组件内的x坐标值
     * Get the X position of this component inside its parent.
     * @return
     */
    public int getX() {
        return x;
    }

    /**
     * 设置组件中其父容器中的x坐标
     * Set the X position of this component inside its parent.
     * @param x
     */
    public void setX(int x) {
        this.x = x;
    }

    /**
     * 获取组件在其父组件内的x坐标值
     * Get the Y position of this component inside its parent.
     * @return
     */
    public int getY() {
        return y;
    }

    /**
     * 设置组件中其父容器内的y坐标
     * Set the Y position of this component inside its parent.
     * @param y
     */
    public void setY(int y) {
        this.y = y;
    }

    /**
     * 重新绘制组件
     * @param cx 绘制起点x坐标增量
     * @param cy 绘制起点y坐标增量
     * @param cwidth 绘制宽度
     * @param cheight 绘制高度
     */
    void repaint(int cx, int cy, int cwidth, int cheight) {

        if (parent != null) {
//			System.out.println("Repaint for "+cx+","+cy);
            parent.repaint(x + cx, y + cy, cwidth, cheight);
        } else { // top level component
            FireScreen.getScreen().repaintScreen(x + cx, y + cy, cwidth, cheight);
        }
    }

    /**
     * 获取偏好尺寸（优先使用该尺寸）
     * @return int[]{prefWidth,prefHeight}
     */
    public int[] getPrefSize() {
        if (prefWidth == -1 || prefHeight == -1) {
            return null;
        }
        return new int[]{prefWidth, prefHeight};
    }
    /**
     * 设置组件的偏好（优先）尺寸
     * @param width
     * @param height
     */
    public void setPrefSize(int width, int height) {
        if (width < 0 || height < 0) {
            throw new IllegalArgumentException("Dimensions can not be negative: " + width + "/" + height);
        }

        prefWidth = width;
        prefHeight = height;
    }

    /**
     * 屏幕指针拖动事件处理函数，需触摸屏
     * @param x
     * @param y
     */
    protected void pointerDragged(int x, int y) {
        if (pointerListener != null) {
            pointerListener.pointerDragged(x, y, this);
        }
    }

    /**
     * 屏幕指针按下事件处理函数，需触摸屏
     * @param x
     * @param y
     */
    protected void pointerPressed(int x, int y) {
        if (pointerListener != null) {
            pointerListener.pointerPressed(x, y, this);
        }
    }

    /**
     * 屏幕指针释放事件处理函数，需触摸屏
     * @param x
     * @param y
     */
    protected void pointerReleased(int x, int y) {
        if (pointerListener != null) {
            pointerListener.pointerReleased(x, y, this);
        }
    }

    /**
     * 按键按下事件处理函数
     * @param keyCode 按键代码
     */
    protected void keyPressed(int keyCode) {
        if (keyListener != null) {
            keyListener.keyPressed(keyCode, this);
        }
    }

    /**
     * 按键释放时间处理函数
     * @param keyCode 按键代码
     */
    protected void keyReleased(int keyCode) {
        if (commandListener != null) {
            if (keyCode == FireScreen.leftSoftKey && leftSoftKeyCommand != null) {
                final Component trigger = this;
                Thread th = new Thread() {

                    public void run() {
                        FireScreen.getScreen().animateLeftSoftKey(trigger);
                        commandListener.commandAction(leftSoftKeyCommand, trigger);
                    }
                };
                th.start();
            } else if (keyCode == FireScreen.rightSoftKey && rightSoftKeyCommand != null) {
                final Component trigger = this;
                Thread th = new Thread() {

                    public void run() {
                        FireScreen.getScreen().animateRightSoftKey(trigger);
                        commandListener.commandAction(rightSoftKeyCommand, trigger);
                    }
                };
                th.start();
            }
        }

        if (keyListener != null) {
            keyListener.keyReleased(keyCode, this);
        }
    }

    /**
     * 按键重复按下事件处理函数，依赖于不同的平台，有些机型不支持连续按键
     * @param keyCode 按键代码
     */
    protected void keyRepeated(int keyCode) {
        if (keyListener != null) {
            keyListener.keyRepeated(keyCode, this);
        }
    }

    /**
     * 获取左软键对应的命令
     * @return
     */
    public Command getLeftSoftKeyCommand() {
        return leftSoftKeyCommand;
    }
    /**
     * 设置左软键命令
     * @param leftSoftKeyCommand
     */
    public void setLeftSoftKeyCommand(Command leftSoftKeyCommand) {
        this.leftSoftKeyCommand = leftSoftKeyCommand;
    }

    /**
     * 获取右软键对应的命令
     * @return
     */
    public Command getRightSoftKeyCommand() {
        return rightSoftKeyCommand;
    }
    /**
     * 设置组件右软键命令
     * @param rightSoftKeyCommand
     */
    public void setRightSoftKeyCommand(Command rightSoftKeyCommand) {
        this.rightSoftKeyCommand = rightSoftKeyCommand;
    }

    /**
     * 获取父组件对象
     * @return
     */
    public Component getParent() {
        return parent;
    }

    /**
     * 获取前景色
     * @return
     */
    public int getForegroundColor() {
        return foregroundColor;
    }
    /**
     * 设置前景色
     * @param foregroundColor
     */
    public void setForegroundColor(int foregroundColor) {
        this.foregroundColor = foregroundColor;
    }

    /**
     * 获取当前背景色
     * @return int
     */
    public int getBackgroundColor() {
        return backgroundColor;
    }

    /**
     * 设置背景色
     * @param backgroundColor
     */
    public void setBackgroundColor(int backgroundColor) {
        this.backgroundColor = backgroundColor;
    }

    /**
     * minSize用于在容器内计算组件布局位置，只能当prefSize未设置时使用minSize，
     * 如果已经设置了prefSize，那么就算minSize比prefSize大，也只能使用prefSize
     * The minSize is used to calculate the layout of the components inside their container by the LayoutManager.
     * The minSize is only used when the prefSize is not set.
     * If the prefSize is set it will be used even if the minSize is bigger than prefSize.
     *
     * @return the minSize dimensions of this Component.
     */
    public int[] getMinSize() {
        return new int[]{0, 0};
    }

    /**
     * 获取当前是使用的字体
     * @return
     */
    public Font getFont() {
        return font;
    }
    /**
     * 设置组件显示文本时使用的字体
     * @param font
     */
    public void setFont(Font font) {
        this.font = font;
    }

    /**
     * 获取组件布局方式，如FireScreen.LEFT
     * @return
     */
    public int getLayout() {
        return layout;
    }
    /**
     * 设置组件的布局方式
     * @param layout
     */
    public void setLayout(int layout) {
        this.layout = layout;
    }

    /**
     * 获取内容宽度（目前只能返回零）
     * @return
     */
    public int getContentWidth() {
        return 0;
    }

    /**
     * 获取内容高度（目前只能返回零）
     * @return
     */
    public int getContentHeight() {
        return 0;
    }
    /**
     * 设置组件是否能获取焦点
     * @param focusable
     */
    public void setFocusable(boolean focusable) {
        this.focusable = focusable;
    }
    /**
     * 设置组件ID
     * @param id
     */
    public void setId(String id) {
        this.id = id;
    }

    /**
     * 获取组件ID
     * @return
     */
    public String getId() {
        return id;
    }
    /**
     * @return 父类的toString方法返回值 + ID
     */
    public String toString() {
        return super.toString() + ((id != null) ? " [" + id + "]" : "");
    }

    /**
     * 获取垂直对齐方式，如FireScreen.TOP
     * @return
     */
    public int getValign() {
        int valign = FireScreen.TOP;

        if ((layout & FireScreen.VCENTER) == FireScreen.VCENTER) {
            valign = FireScreen.VCENTER;
        } else if ((layout & FireScreen.TOP) == FireScreen.TOP) {
            valign = FireScreen.TOP;
        } else if ((layout & FireScreen.BOTTOM) == FireScreen.BOTTOM) {
            valign = FireScreen.BOTTOM;
        }

        return valign;
    }

    /**
     * 获取水平对齐方式，由FireScreen常量表示，如FireScreen.LEFT
     * @return
     */
    public int getHalign() {
        int halign = FireScreen.LEFT;

        if ((layout & FireScreen.CENTER) == FireScreen.CENTER) // hcenter
        {
            halign = FireScreen.CENTER;
        } else if ((layout & FireScreen.LEFT) == FireScreen.LEFT) {
            halign = FireScreen.LEFT;
        } else if ((layout & FireScreen.RIGHT) == FireScreen.RIGHT) {
            halign = FireScreen.RIGHT;
        }
        return halign;
    }

    /**
     * 获取组件是否会显示边框
     * @return
     */
    public boolean isBorder() {
        return border;
    }

    /**
     * 设置组件是否会显示边框
     * @param border
     */
    public void setBorder(boolean border) {
        this.border = border;
    }

    /**
     * 获取组件是否可见
     * @return
     */
    public boolean isVisible() {
        return visible;
    }
    /**
     * 设置组件是否可见
     * @param visible
     */
    public void setVisible(boolean visible) {
        this.visible = visible;
    }

    /**
     * 将组件移动指定的坐标增量
     * @param dx x坐标
     * @param dy y坐标
     */
    public void move(int dx, int dy) {
        repaint();
        this.x += dx;
        this.y += dy;
    }
    /**
     * 设置组件左上角的位置
     * @param x x坐标
     * @param y y坐标
     */
    public void setPosition(int x, int y) {
        repaint();
        this.x = x;
        this.y = y;
    }

    /**
     * 获取组件对应的动画对象
     * @return Animation
     */
    public Animation getAnimation() {
        return animation;
    }

    /**
     * 设置组件要使用的动画对象
     * @param animation
     */
    public void setAnimation(Animation animation) {
        if (animation.parent != null) {
            animation.parent.animation = null;
        }
        animation.parent = this;
        this.animation = animation;
    }
}