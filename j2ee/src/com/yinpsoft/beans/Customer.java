package com.yinpsoft.beans;

import java.util.Date;

/**
 * Customer entity. @author MyEclipse Persistence Tools
 */

public class Customer implements java.io.Serializable {

	// Fields

	private String customerid;
	private Customertype customertype;
	private String customername;
	private String sex;
	private String memo;
	private String isvoid;
	private Date created;

	// Constructors

	/** default constructor */
	public Customer() {
	}

	/** full constructor */
	public Customer(Customertype customertype, String customername, String sex,
			String memo, String isvoid, Date created) {
		this.customertype = customertype;
		this.customername = customername;
		this.sex = sex;
		this.memo = memo;
		this.isvoid = isvoid;
		this.created = created;
	}

	// Property accessors

	public String getCustomerid() {
		return this.customerid;
	}

	public void setCustomerid(String customerid) {
		this.customerid = customerid;
	}

	public Customertype getCustomertype() {
		return this.customertype;
	}

	public void setCustomertype(Customertype customertype) {
		this.customertype = customertype;
	}

	public String getCustomername() {
		return this.customername;
	}

	public void setCustomername(String customername) {
		this.customername = customername;
	}

	public String getSex() {
		return this.sex;
	}

	public void setSex(String sex) {
		this.sex = sex;
	}

	public String getMemo() {
		return this.memo;
	}

	public void setMemo(String memo) {
		this.memo = memo;
	}

	public String getIsvoid() {
		return this.isvoid;
	}

	public void setIsvoid(String isvoid) {
		this.isvoid = isvoid;
	}

	public Date getCreated() {
		return this.created;
	}

	public void setCreated(Date created) {
		this.created = created;
	}

}