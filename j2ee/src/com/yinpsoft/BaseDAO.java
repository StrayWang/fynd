package com.yinpsoft;

import org.hibernate.Session;
import org.hibernate.Transaction;

/**
 * Data access object (DAO) for domain model
 * @author MyEclipse Persistence Tools
 */
public class BaseDAO implements IBaseHibernateDAO {
	
	private static Transaction transaction = null;
	private static Session session = null;
		
	public static void Commit(){
		if (!transaction.wasCommitted()){
			transaction.commit();
		}		
	}
	
	public static void Rollback(){
		if (!transaction.wasRolledBack()){
			transaction.rollback();
		}
	}
	
	public Session getSession() {
		session = ApplicationSessionFactory.getSession();
		transaction = session.beginTransaction();
		return session;
	}
	
}