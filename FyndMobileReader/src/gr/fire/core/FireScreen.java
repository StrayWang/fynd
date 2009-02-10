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

import gr.fire.ui.SoftKeyAnimation;
import gr.fire.util.Log;
import gr.fire.util.Queue;

import javax.microedition.lcdui.Canvas;
import javax.microedition.lcdui.Command;
import javax.microedition.lcdui.Display;
import javax.microedition.lcdui.Displayable;
import javax.microedition.lcdui.Font;
import javax.microedition.lcdui.Graphics;
import javax.microedition.lcdui.Image;
import javax.microedition.lcdui.game.Sprite;

/**
 * @author padeler
 *
 */
public class FireScreen extends Canvas implements Runnable {

    public static final byte NORMAL = 0x00;
    public static final byte LANDSCAPERIGHT = 0x01;
    public static final byte LANDSCAPELEFT = 0x02;
    public static final int NONE = 0x00000000;
    public static final int CENTER = 0x00000001;
    public static final int RIGHT = 0x00000002;
    public static final int LEFT = 0x00000004;
    public static final int TOP = 0x00000008;
    public static final int BOTTOM = 0x00000010;
    public static final int VCENTER = 0x00000020;
    /* Key mapping info. */
    public static int leftSoftKey = -6;
    public static int rightSoftKey = -7;
    private static final Object[][] keyMaps = {
        {"Nokia", new Integer(-6), new Integer(-7)}, {"ricsson", new Integer(-6), new Integer(-7)},
        {"iemens", new Integer(-1), new Integer(-4)}, {"otorola", new Integer(-21), new Integer(-22)},
        {"harp", new Integer(-21), new Integer(-22)}, {"j2me", new Integer(-6), new Integer(-7)}
    };
    /**
     * ZINDEX is the location a component on the Z axis (depth) it controls in which order the
     * Component will be painted.
     * <br/>
     * The current component set in the methods getCurrent and setCurrent is always considered the component on zindex=0.
     * <br/>
     * The indexes should be used as follows (this is a recomendation, it is not forced): <br/>
     * -3, -2, -1 : Backgrounds and background animations <br/>
     *  0, 1, 2, 3 : Panels, Containers, popup menus and user traversable components in general. <br/>
     *  4, 5, 6, 7, ... : Animations, effects, transition animations, mouse pointers, etc. <br/>
     *
     * @see #setCurrent(Component)
     * @see #getCurrent()
     *
     */
    public static final int ZINDEX_MAX = 6;
    /**
     * @see #ZINDEX_MAX
     */
    public static final int ZINDEX_MIN = -3;
    private static FireScreen singleton = null;
    private static Theme theme = new Theme(); // default theme.
    private static Font navbarFont;
    /* ***************** Support variables ***************************** */
    private Display display = null;
    private Image offscreen; // offscreen image used when rendering in landscape modes.
    private int orientation = NORMAL;
    private Queue animationQueue;
    /** Vector containing all the stacked components currently open on the scren. */
    private Component selectedComponent = null; // holds the currently selected component.
    private Component[] componentSlots = new Component[(ZINDEX_MAX - ZINDEX_MIN) + 1];
    private Thread animationThread;

    private FireScreen(Display display) {
        /* **** Hack for Motorola phones ********** Thanks to Maxim Blagov */
        /* **** Some (if not all) Motorola phones, return "j2me" to getProperty("microedition.platform") */
        String platform = null;
        String prop = System.getProperty("com.mot.carrier.URL");
        if (prop != null) {
            platform = "motorola";
        } else {
            try {
                Class.forName("com.motorola.graphics.j3d.Graphics3D");
                platform = "motorola";
            } catch (Throwable e) {
            }
        }

        if (platform == null) // ok its propably not a motorola phone.
        {
            platform = System.getProperty("microedition.platform");
        }
        /* ******************************************************** */

        for (int i = 0; i < keyMaps.length; ++i) {
            String manufacturer = (String) keyMaps[i][0];

            if (platform.indexOf(manufacturer) != -1) {
                if (i == 1) // ta sony ericsson exoun enalaktika keys sta p800/p900/p908/p802
                {
                    if (platform.indexOf("P900") != -1 || platform.indexOf("P908") != -1) {
                        leftSoftKey = ((Integer) keyMaps[i][2]).intValue();
                        rightSoftKey = ((Integer) keyMaps[i][1]).intValue();
                    } else {
                        leftSoftKey = ((Integer) keyMaps[i][1]).intValue();
                        rightSoftKey = ((Integer) keyMaps[i][2]).intValue();
                    }
                } else {
                    leftSoftKey = ((Integer) keyMaps[i][1]).intValue();
                    rightSoftKey = ((Integer) keyMaps[i][2]).intValue();
                }
                break;
            }
        }


        this.display = display;

        animationQueue = new Queue();
        animationThread = new Thread(this);
        animationThread.start();
    }

    public void paint(Graphics g) {
        int width = getWidth();
        int height = getHeight();

        int clipX = g.getClipX(), clipY = g.getClipY(), clipW = g.getClipWidth(), clipH = g.getClipHeight();
        int trX = g.getTranslateX();
        int trY = g.getTranslateY();


        Graphics dest;
        if (orientation != NORMAL) {
            if (offscreen == null || offscreen.getWidth() != width || offscreen.getHeight() != height) {
                offscreen = Image.createImage(width, height);
            }
            dest = offscreen.getGraphics();

            // invert the clip and translate information.
            int t = trX;
            if (orientation == LANDSCAPELEFT) {
                trX = -trY;
                trY = t;
                t = clipX;
                clipX = -clipY;
                clipY = t;
            } else {
                trX = trY;
                trY = -t;
                t = clipX;
                clipX = clipY;
                clipY = -t;
            }

            t = clipW;
            clipW = clipH;
            clipH = t;
        } else {
            dest = g;
        }


        /* ***** Clean the area that will be repainted. ***** */
        // clean the bg to white...
        dest.setColor(0x00FFFFFF);
        dest.fillRect(clipX, clipY, clipW, clipH); // this will only repaint the clipped region.

        /* ************ paint the fireScreen animations behind the components. ***************** */
        for (int i = 0; i < componentSlots.length; ++i) {
            Component paintable = componentSlots[i];
            if (paintable != null && paintable.visible && paintable.intersects(clipX, clipY, clipW, clipH)) {
                dest.clipRect(paintable.x, paintable.y, paintable.width, paintable.height);
                dest.translate(paintable.x, paintable.y);

                if (!paintable.valid) {
                    if (!(paintable instanceof Panel) && (paintable instanceof Container)) {
                        ((Container) paintable).layoutManager.layoutContainer((Container) paintable);
                    }

                    paintable.validate();
                }

                // ok, now paint or animate the component.
                if (paintable.animation != null) {
                    paintable.animation.paint(dest);
                } else {
                    paintable.paint(dest);
                    if ((paintable instanceof Container || paintable.isFocusable()) && (clipY + clipH > height - navbarFont.getHeight())) {
                        // now repaint the softkeys if needed
                        repaintSoftKeys(paintable.leftSoftKeyCommand, paintable.rightSoftKeyCommand, dest);
                    }
                }

                dest.translate(-dest.getTranslateX() + trX, -dest.getTranslateY() + trY);
                dest.setClip(clipX, clipY, clipW, clipH);
            }

        }

        /* **** Finally paint the offscreen. This step is only used when drawing in landscape modes. **** */
        switch (orientation) {
            case NORMAL:
                break;
            case LANDSCAPELEFT:
                g.drawRegion(offscreen, 0, 0, width, height, Sprite.TRANS_ROT270, 0, 0, Graphics.TOP | Graphics.LEFT);
                break;
            case LANDSCAPERIGHT:
                g.drawRegion(offscreen, 0, 0, width, height, Sprite.TRANS_ROT90, 0, 0, Graphics.TOP | Graphics.LEFT);
                break;
        }

    }

    public void registerAnimation(Animation anim) {

        animationQueue.add(anim);
        if (anim.parent != null) {
            anim.parent.animation = anim;
        }
    }

    public void removeAnimation(Animation anim) {
        animationQueue.remove(anim);
        if (anim.parent != null) {
            anim.parent.animation = null;
        }
    }

    void animateLeftSoftKey(Component trigger) {
        int keyH = navbarFont.getHeight();
        int dh = getHeight();

        String str = trigger.leftSoftKeyCommand.getLabel();
        SoftKeyAnimation anim = new SoftKeyAnimation(str);
        anim.setPosition(0, dh - keyH);
        anim.setHeight(keyH);
        anim.setWidth(navbarFont.stringWidth(str));
        addComponent(anim, 5);
    }

    void animateRightSoftKey(Component trigger) {
        String str = trigger.rightSoftKeyCommand.getLabel();
        int keyH = navbarFont.getHeight();
        int dh = getHeight();
        int dw = getWidth();
        int keyW = navbarFont.stringWidth(str);

        SoftKeyAnimation anim = new SoftKeyAnimation(str);
        anim.setPosition(dw - keyW, dh - keyH);
        anim.setHeight(keyH);
        anim.setWidth(keyW);
        addComponent(anim, 5);
    }

    private void repaintSoftKeys(Command left, Command right, Graphics dest) {
        if (left == null && right == null) {
            return;
        }

        dest.setFont(navbarFont);
        int keyH = navbarFont.getHeight();
        int dw = getWidth();
        int dh = getHeight();

        dest.translate(-dest.getTranslateX(), dh - dest.getTranslateY() - keyH);
        dest.clipRect(0, 0, dw, keyH);

        int navbarBgColor = theme.getIntProperty("navbar.bg.color");
        int navbarFgColor = theme.getIntProperty("navbar.fg.color");

        if (left != null) {
            int keyW = navbarFont.stringWidth(left.getLabel());
            if (navbarBgColor != Theme.TRANSPARENT) // not transparent bg
            {
                dest.setColor(navbarBgColor);
                dest.fillRect(0, 0, keyW, keyH);
            }
            dest.setColor(navbarFgColor);
            dest.drawString(left.getLabel(), 0, 0, Graphics.TOP | Graphics.LEFT);
        }

        if (right != null) {
            int keyW = navbarFont.stringWidth(right.getLabel());

            if (navbarBgColor != Theme.TRANSPARENT) // not transparent bg
            {
                dest.setColor(navbarBgColor);
                dest.fillRect(dw - keyW, 0, keyW, keyH);
            }
            dest.setColor(navbarFgColor);
            dest.drawString(right.getLabel(), dw - keyW, 0, Graphics.TOP | Graphics.LEFT);
        }
    }

    void inValidateTopLevelContainer() {
        if (componentSlots[-ZINDEX_MIN] != null) {
            componentSlots[-ZINDEX_MIN].valid = false;
            repaint();
        }
    }

    /**
     * Returns the current panel set on the FireScreen.
     * @return
     */
    public Component getCurrent() {
        return componentSlots[-ZINDEX_MIN];
    }

    /**
     * Set a Displayable to the FireScreen.
     * @param p
     */
    public void setCurrent(Displayable d) {
        display.setCurrent(d);
    }

    public void addComponent(Component c, int zidx) {
        if (zidx < ZINDEX_MIN || zidx > ZINDEX_MAX) {
            throw new IllegalArgumentException("zindex must be between [" + ZINDEX_MIN + "," + ZINDEX_MAX + "].");
        }

        int zindex = zidx - ZINDEX_MIN;

        if (c.parent != null) {
            if (c.parent instanceof Container) {
                ((Container) c.parent).remove(c);
            }

            c.parent = null;
        }

        if (c.valid == false) {
            if (!(c instanceof Panel) && (c instanceof Container)) {
                ((Container) c).layoutManager.layoutContainer((Container) c);
            }

            c.validate();
        }

        Component last = componentSlots[zindex];
        if (last != null) {
            removeComponent(zidx);
        }

        componentSlots[zindex] = c;

        if (c instanceof Animation) {
            registerAnimation((Animation) c);
        } else {
            if (display.getCurrent() != this) // no animation if previous screen was not a FireScreen component.
            {/* when moving from one Fire component to another we want to deselect the last selected. */
                /* When moving from a textbox or other displayable to a fire component we want to keep any previously selected component knowledge */
                display.setCurrent(this);
                repaintScreen(0, 0, getWidth(), getHeight());
                last = null;
            } else {
                setSelectedComponent(null); // this will allow for returning to the selected input field after editing it.
            }

            if (c.animation != null) {
                registerAnimation(c.animation);
            }
        }
        c.repaint();
    }

    public boolean removeTopContainer() {
        for (int i = componentSlots.length - 1; i >= 0; --i) {
            Component c = componentSlots[i];
            if (c != null && (c instanceof Animation) == false) {
                setSelectedComponent(null);
                componentSlots[i] = null;
                repaint();
                return true;
            }
        }
        return false;
    }

    public boolean removeComponent(Component c) {
        if (c == null) {
            throw new NullPointerException("Cannot remove null component.");
        }

        for (int i = componentSlots.length - 1; i >= 0; --i) {
            if (componentSlots[i] == c) {
                return removeComponent(i + ZINDEX_MIN);
            }
        }
        return false;
    }

    public boolean removeComponent(int zindex) {
        if (zindex < ZINDEX_MIN || zindex > ZINDEX_MAX) {
            throw new IllegalArgumentException("zindex must be between [" + ZINDEX_MIN + "," + ZINDEX_MAX + "].");
        }

        if (componentSlots[zindex - ZINDEX_MIN] == null) {
            return false;
        }

        Component c = componentSlots[zindex - ZINDEX_MIN];

        if (c instanceof Animation) { // remove the animation from the queue
            removeAnimation((Animation) c);
        }
        if (c.animation != null) {
            removeAnimation(c.animation);
        }

        animationQueue.removeAllWithParent(c);

        componentSlots[zindex - ZINDEX_MIN] = null;
        repaint();

        return true;
    }

    /**
     * Shows the container p on the screen with the supplied animation direction.
     * @param p
     * @param animDirection
     */
    public void setCurrent(Component p) {
        addComponent(p, 0);
    }
    private boolean destroyed = false;

    public void run() {
        long minLoopTime = 30; // dont loop faster than this period, in order to avoid busy waits
        long start, period;
        try {
            while (!destroyed) {
                start = System.currentTimeMillis();
                Animation anim = (Animation) animationQueue.getNext();
                Component owner = anim.parent;
                if (anim.isRunning() == false)// animation completed. remove it from the queue.
                {
                    removeAnimation(anim);
                    removeComponent(anim);
                    // ask for a repaint of its owner component.
                    if (owner != null) {
                        owner.repaint();
                    } else {
                        repaintScreen(anim.getX(), anim.getY(), anim.getWidth(), anim.getHeight()); // repaint the area of the animation.
                    }
                    continue;
                }

                if (anim.step() && anim.visible) {
                    anim.repaint();
                }
                period = System.currentTimeMillis() - start;
                if (period < minLoopTime) {
                    try {
                        Thread.sleep(minLoopTime - period);
                    } catch (InterruptedException e) {
                        Log.logError("Interrupted inside animation thread.", e);
                    }
                }
            }
        } catch (Throwable e) {
            Log.logError("Animation thread exception", e);
        }
    }

    /**
     * Used to create and retrieve the FireScreen singleton.
     * @param display, if not null and its the first call of the method, a FireScreen instance for this display is created.
     * @return the FireScreen singleton.
     */
    public static FireScreen getScreen(Display display) {
        if (display != null && singleton == null) {
            singleton = new FireScreen(display);
        }
        return singleton;
    }

    /**
     * Used to create and retrieve the FireScreen singleton.
     * @return the FireScreen singleton.
     */
    public static FireScreen getScreen() {
        if (singleton == null) {
            throw new NullPointerException("FireScreen is not initialized.");
        }
        return singleton;
    }

    public static Theme getTheme() {
        return theme;
    }

    public static void setTheme(Theme theme) {
        if (theme != null) {
            FireScreen.theme = theme;
        } else {
            FireScreen.theme = new Theme();
        }

        navbarFont = theme.getFontProperty("navbar.font");
    }

    protected void pointerDragged(int x, int y) {
        if (orientation != NORMAL) { // screen is on landscape mode, width is height and vise versa
            int t = x;
            if (orientation == LANDSCAPELEFT) {
                x = super.getHeight() - y;
                y = t;
            } else {
                x = y;
                y = super.getWidth() - t;
            }
        }
        for (int i = componentSlots.length - 1; i >= 0; --i) {
            Component cmp = componentSlots[i];
            if (cmp != null && (cmp instanceof Container || cmp.isFocusable())) { // only send events to containers or focusable components
                cmp.pointerDragged(x - cmp.x, y - cmp.y);
                break;// only send the pointer event once.
            }
        }
    }

    protected void pointerPressed(int x, int y) {
        if (orientation != NORMAL) { // screen is on landscape mode, width is height and vise versa
            int t = x;
            if (orientation == LANDSCAPELEFT) {
                x = super.getHeight() - y;
                y = t;
            } else {
                x = y;
                y = super.getWidth() - t;
            }
        }
        for (int i = componentSlots.length - 1; i >= 0; --i) {
            Component cmp = componentSlots[i];
            if (cmp != null && (cmp instanceof Container || cmp.isFocusable())) { // only send events to containers or focusable components
                cmp.pointerPressed(x - cmp.x, y - cmp.y);
                break;// only send the pointer event once.
            }
        }
    }

    protected void pointerReleased(int x, int y) {
        if (orientation != NORMAL) { // screen is on landscape mode, width is height and vise versa
            int t = x;
            if (orientation == LANDSCAPELEFT) {
                x = super.getHeight() - y;
                y = t;
            } else {
                x = y;
                y = super.getWidth() - t;
            }
        }
        for (int i = componentSlots.length - 1; i >= 0; --i) {
            Component cmp = componentSlots[i];
            if (cmp != null && (cmp instanceof Container || cmp.isFocusable())) { // only send events to containers or focusable components
                cmp.pointerReleased(x - cmp.x, y - cmp.y);
                break;// only send the pointer event once.
            }
        }
    }

    protected void keyPressed(int keyCode) {
        if (selectedComponent != null) {
            selectedComponent.keyPressed(keyCode);
        } else {
            for (int i = componentSlots.length - 1; i >= 0; --i) {
                Component cmp = componentSlots[i];
                if (cmp != null && (cmp instanceof Container || cmp.isFocusable())) { // only send events to containers or focusable components
                    cmp.keyPressed(keyCode);
                    break;// only send the key event once.
                }
            }
        }
    }

    public int getGameAction(int keyCode) {
        return super.getGameAction(keyCode);
    }

    protected void keyReleased(int k) {
        Component current = selectedComponent;
        if (current != null && current.selected &&
                !((k == leftSoftKey && current.leftSoftKeyCommand == null) ||// must send the event to the container or panel
                (k == rightSoftKey && current.rightSoftKeyCommand == null))) {

            current.keyReleased(k); // keyReleased might change the selectedComponent
            if (current.selected) {
                return;
            }
        // else component is not anymore selected due to keyReleased event. send the event to the containers
        }

        for (int i = componentSlots.length - 1; i >= 0; --i) {
            Component cmp = componentSlots[i];
            if (cmp != null && (cmp instanceof Container || cmp.isFocusable())) { // only send events to containers or focusable components
                cmp.keyReleased(k);
                break;// only send the key event once.
            }
        }
    }

    protected void keyRepeated(int keyCode) {
        if (selectedComponent != null) {
            selectedComponent.keyRepeated(keyCode);
        } else {
            for (int i = componentSlots.length - 1; i >= 0; --i) {
                Component cmp = componentSlots[i];
                if (cmp != null && (cmp instanceof Container || cmp.isFocusable())) { // only send events to containers or focusable components
                    cmp.keyRepeated(keyCode);
                    break;// only send the key event once.
                }
            }
        }
    }

    protected void sizeChanged(int w, int h) {
        super.sizeChanged(w, h);
        sizeChangedImpl(w, h);
    }

    private void sizeChangedImpl(int w, int h) {
        offscreen = null;

        for (int i = componentSlots.length - 1; i >= 0; --i) {
            Component cmp = componentSlots[i];
            if (cmp != null) {
                cmp.valid = false;
            }
        }
        repaint();
    }

    /**
     * Returns the width of this FireScreen. If the screen is in landscape mode, it will return the real height of the screen.
     * @see javax.microedition.lcdui.Displayable#getWidth()
     */
    public int getWidth() {
        if (orientation == NORMAL) {
            return super.getWidth();
        }
        return super.getHeight();
    }

    /**
     * Returns the height of this FireScreen. If the screen is in landscape mode, it will return the real width of the screen.
     * @see javax.microedition.lcdui.Displayable#getHeight()
     */
    public int getHeight() {
        if (orientation == NORMAL) {
            return super.getHeight();
        }
        return super.getWidth();
    }

    public int getOrientation() {
        return orientation;
    }

    public void setOrientation(int orientation) {
        if (orientation != NORMAL && orientation != LANDSCAPELEFT && orientation != LANDSCAPERIGHT) {
            throw new IllegalArgumentException("Unknown orientation value " + orientation);
        }
        this.orientation = orientation;
        this.sizeChangedImpl(super.getWidth(), super.getHeight());
    }

    Component getSelectedComponent() {
        return selectedComponent;
    }

    void setSelectedComponent(Component newSelectedComponent) {
        if (newSelectedComponent == selectedComponent) {
            return; // nothing to do here
        }
        if (selectedComponent != null && selectedComponent.selected) {
            selectedComponent.setSelected(false);
        }
        this.selectedComponent = newSelectedComponent;
    }

    public void repaintScreen(int cx, int cy, int cwidth, int cheight) {
        //Log.logDebug("Repaint request for "+cx+","+cy+" / "+cwidth+","+cheight);
        switch (orientation) {
            case NORMAL:
                repaint(cx, cy, cwidth, cheight);
                break;
            case LANDSCAPELEFT:
                repaint(cy, -cx, cheight, cwidth);
                break;
            case LANDSCAPERIGHT:
                repaint(-cy, cx, cheight, cwidth);
                break;
        }
    }

    public void destroy() {
        destroyed = true;
//		try
//		{
//			animationThread.join();
//		} catch (InterruptedException e)
//		{
//			e.printStackTrace();
//		}
    }
}