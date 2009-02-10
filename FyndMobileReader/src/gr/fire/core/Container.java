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

import gr.fire.util.Log;
import java.util.Vector;
import javax.microedition.lcdui.Canvas;
import javax.microedition.lcdui.Graphics;

/**
 * A container is a Components that can contain other components. The components
 * inside the Container are layed out using a layout manager. The default layout
 * manager is null (AbsolutLayout). The default layout manager lays out the
 * components according to their preset component.x and component.y values and
 * their preffered sizes (if set).
 * 
 * A Container can have leftSoftKey and rightSoftKey commands assotiated with
 * it.
 * 
 * @author padeler
 * 
 */
public class Container extends Component {

    protected Vector components;
    LayoutManager layoutManager = AbsolutLayout.defaultLayout;
    private Vector focusableComponents = null;

    public Container() {
        this(null);
    }

    public Container(LayoutManager manager) {
        components = new Vector();
        if (manager != null) {
            layoutManager = manager;
        }
        setFocusable(true);
    }

    public boolean isFocusable() {
        return false;
    }

    public void add(Component cmp, Object constrains) {
        if (cmp.parent != null) { // remove it from its current parent.
            if (cmp.parent instanceof Container) {
                ((Container) cmp.parent).remove(cmp);
            }
        }
        cmp.parent = this;
        cmp.constrains = constrains;
        components.addElement(cmp);
        valid = false; // needs validation.
    }

    public void add(Component cmp) {
        add(cmp, null);
    }

    public void remove(Component cmp) {
        cmp.parent = null;
        components.removeElement(cmp);
        valid = false;
    }

    public void paint(Graphics g) {
        int originalTrX = g.getTranslateX();
        int originalTrY = g.getTranslateY();
        int originalClipX = g.getClipX();
        int originalClipY = g.getClipY();
        int originalClipWidth = g.getClipWidth();
        int originalClipHeight = g.getClipHeight();

        // paint background in the repainting area.
        if (backgroundColor != Theme.TRANSPARENT) {
            g.setColor(backgroundColor);
            g.fillRect(originalClipX, originalClipY, originalClipWidth, originalClipHeight);
        }

        for (int i = 0; i < components.size(); ++i) {
            Component cmp = (Component) components.elementAt(i);

            if (cmp.valid == false) { // one of my components needs validation. This means that its
                // size may have changed.
                FireScreen.getScreen().inValidateTopLevelContainer();
                return;
            }

            if (cmp.visible && cmp.intersects(originalClipX, originalClipY, originalClipWidth, originalClipHeight)) {
                g.clipRect(cmp.x, cmp.y, cmp.width, cmp.height);
                g.translate(cmp.x, cmp.y);

                if (cmp.animation == null) {
                    cmp.paint(g);
                } else {
                    cmp.animation.paint(g);
                }

                // return to the coordinates of this component.
                g.translate(originalTrX - g.getTranslateX(), originalTrY - g.getTranslateY());
                g.setClip(originalClipX, originalClipY, originalClipWidth, originalClipHeight);
            }
        }
        if (border) {
            g.setColor(FireScreen.getTheme().getIntProperty("border.color"));
            g.drawRect(0, 0, width - 1, height - 1);
        }
    }

    public void setLayoutManager(LayoutManager manager) {
        if (manager == null) {
            layoutManager = AbsolutLayout.defaultLayout;
        } else {
            layoutManager = manager;
        }
        valid = false;
    }

    public void validate() {
        focusableComponents = null;

        for (int i = 0; i < components.size(); ++i) {
            Component c = ((Component) components.elementAt(i));
            c.validate();
            if (c.valid == false) {
                Log.logWarn("Failed to validate component " + c.getClass().getName());
            }
        }
        valid = true;
    }

    protected void pointerReleased(int x, int y) {
        for (int i = 0; i < components.size(); ++i) {
            Component cmp = (Component) components.elementAt(i);
            int cx = x - cmp.x;
            int cy = y - cmp.y;
            if (cmp.contains(cx, cy)) {
                if (cmp.isFocusable() || cmp instanceof Container) { // only focusable components receive events
                    cmp.pointerReleased(cx, cy);
                    if ((cmp instanceof Container) == false) // do not select recursively all containers
                    {
                        FireScreen.getScreen().setSelectedComponent(cmp);
                    }
                }
                break;
            }
        }
        super.pointerReleased(x, y);
    }

    protected void keyReleased(int keyCode) {
        if (keyCode == FireScreen.leftSoftKey || keyCode == FireScreen.rightSoftKey) {
            super.keyReleased(keyCode);
            return;
        }

        FireScreen screen = FireScreen.getScreen();
        int gameCode = screen.getGameAction(keyCode);

        if (gameCode == Canvas.UP || gameCode == Canvas.DOWN || gameCode == Canvas.LEFT || gameCode == Canvas.RIGHT) {
            // first find the next selectable component.
            if (focusableComponents == null) {
                focusableComponents = generateListOfFocusableComponents(true);
            }

            int step;
            int index;
            if (gameCode == Canvas.UP || gameCode == Canvas.LEFT) {
                step = -1; // previous component
                index = focusableComponents.size() - 1;
            } else {
                step = +1; // next component
                index = 0;
            }
            Component lastSelected = screen.getSelectedComponent();
            if (lastSelected != null) {
                int lastPos = focusableComponents.indexOf(lastSelected);
                if (lastPos > -1) {
                    index = lastPos + step;
                }
                screen.setSelectedComponent(null); // deselect last selected
            }

            if (index > -1 && index < focusableComponents.size()) {
                Component next = (Component) focusableComponents.elementAt(index);
                next.keyReleased(keyCode);
                screen.setSelectedComponent(next);
            }
        }
        super.keyReleased(keyCode);
    }

    /**
     * Creates a vector with all the focusable children of this container. If recursive is true, it will descent into its children
     * Containers recursively and add their focusable components to the list.
     *
     * @param recursive
     * @return
     */
    public Vector generateListOfFocusableComponents(boolean recursive) {
        Vector res = new Vector();

        for (int i = 0; i < components.size(); ++i) {
            Component cmp = (Component) components.elementAt(i);
            if (cmp.isFocusable() || cmp instanceof Panel) {
                res.addElement(cmp);
            } else if (recursive && cmp instanceof Container) {
                Container container = (Container) cmp;
                Vector v = container.generateListOfFocusableComponents(recursive);
                for (int j = 0; j < v.size(); ++j) {
                    res.addElement(v.elementAt(j));
                }
            }
        }
        return res;
    }

    public int countComponents() {
        return components.size();
    }

    public Component getComponent(int i) {
        return (Component) components.elementAt(i);
    }

    public int getComponentIndex(Component cmp) {
        return components.indexOf(cmp);
    }

    public int[] getMinSize() {
        if (parent == null) {
            FireScreen screen = FireScreen.getScreen();
            return new int[]{screen.getWidth(), screen.getHeight()};
        }
        return super.getMinSize();
    }

    public String toString() {
        return super.toString() + " (" + components.size() + ")";
    }
}